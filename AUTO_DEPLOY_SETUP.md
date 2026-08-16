# Auto-deploy setup

SSH isn't network-reachable on this hosting account (timed out against
both the domain and the shared IP `199.188.200.152`, on ports 22 and
2222), so this uses a webhook instead: GitHub calls a small PHP script on
your server over plain HTTPS on every push, and that script does the
`git pull` + deploy using PHP's `exec()` — no SSH needed.

cPanel account: username `mfmlbbcm`, home directory `/home/mfmlbbcm`.

Two independent deploy targets. Each entry script is fully
self-contained (no shared file required from the repo clone) so a
fresh clone that isn't yet on the right branch can't break the script
that's supposed to fix that:

| | Staging | Production |
|---|---|---|
| Domain | `tmpm.mfmlekkiphaseone.org` | `app.mfmlekkiphaseone.org` (live) |
| Branch | `upgrade/churchbackend-merge` | `main` |
| Entry script | `deploy/webhook-staging.php` | `deploy/webhook-production.php` |
| Config file | `/home/mfmlbbcm/church-deploy-config.php` | `/home/mfmlbbcm/deploy-config.php` |
| Database | fresh, to be created | existing production DB |

(`church.mfmlekkiphaseone.org` was an earlier staging attempt abandoned
after a persistent, unresolved vhost-level inconsistency — its own
directory listing showed files that direct requests 404'd on, even from
independent networks. `tmpm` replaced it.)

**Do staging first.** It's where the whole pipeline (including automatic
migrations) gets proven safe before it ever touches the real site.

---

## Staging — tmpm.mfmlekkiphaseone.org

### 1. Create the subdomain
cPanel → **Domains → Create A New Domain**. Domain: `tmpm.mfmlekkiphaseone.org`.
Note the **document root** cPanel assigns (it'll suggest one — you can
accept the default).

### 2. Create a fresh database
cPanel → **MySQL® Databases**.
- Create a new database
- Create a new database user with its own password
- Add that user to the new database with **All Privileges**

Keep the database name, username, and password — you'll need them in step 6.

### 3. Clone the repo
cPanel → **Git™ Version Control → Create**.
- Clone URL: `https://github.com/Innovatyou/mfmlekkibackend.git`
- Repository Path: `/home/mfmlbbcm/repositories/tmpm`
- Branch: `upgrade/churchbackend-merge`

(Private repo → use a [fine-grained Personal Access Token](https://github.com/settings/tokens),
read-only, scoped to just this repo — not your GitHub password.) Don't
worry if cPanel's "Checked-Out Branch" display doesn't reliably stick to
this branch — the deploy script does its own `git fetch` + `reset --hard`
to whatever branch its config specifies on every run, regardless of what
cPanel's UI shows.

### 4. Create the staging config
cPanel File Manager → create `/home/mfmlbbcm/church-deploy-config.php`:

```php
<?php
$secret        = 'PASTE_A_DIFFERENT_LONG_RANDOM_SECRET_THAN_PRODUCTION';
$repoDir       = '/home/mfmlbbcm/repositories/tmpm';
$deployDir     = '/home/mfmlbbcm/PASTE_STAGING_DOCUMENT_ROOT_FROM_STEP_1';
$branch        = 'upgrade/churchbackend-merge';
$runMigrations = true;
```

Ask me for a random secret if you'd rather not generate one by hand —
and never reuse production's secret here.

### 5. Make the webhook reachable
Copy `/home/mfmlbbcm/repositories/tmpm/deploy/webhook-staging.php`
→ into the staging document root as `deploy-hook.php`, unchanged. It's
fully self-contained (no require of another repo file), so this one
paste is genuinely a one-time step — a future change to the deploy
*config* (secret, paths, branch) is a config-file edit, but a future
change to the deploy *logic itself* would need this file re-pasted too.

Public URL: `https://tmpm.mfmlekkiphaseone.org/deploy-hook.php`

### 6. Add the GitHub webhook
Repo → **Settings → Webhooks → Add webhook**
- Payload URL: `https://tmpm.mfmlekkiphaseone.org/deploy-hook.php`
- Content type: `application/json`
- Secret: the one from step 4
- Events: **Just the push event**

### 7. First push — expect it to partially fail, that's normal
Push any commit to `upgrade/churchbackend-merge` (or use GitHub's webhook
page → **Recent Deliveries → Redeliver**). The code will sync
successfully, but `php spark migrate` will fail — there's no
`app/Config/Database.php` yet, since that file is deliberately excluded
from every sync. Check `deploy.log` in the staging document root
to confirm that's what happened (not something else).

### 8. Staging: first-time config (only once)
Now that the code is there, cPanel File Manager → in the staging document
root, create/edit:

- **`app/Config/Database.php`** — set `'database'` to the DB name from
  step 2, `'username'`/`'password'` to that DB user's credentials,
  `'hostname' => 'localhost'`.
- **`app/Config/App.php`** — set `$baseURL = 'https://tmpm.mfmlekkiphaseone.org/';`
- **`.env`** — at minimum, add the license-activation block so the
  `License` filter doesn't lock you out:
  ```
  ACTIVATION_CODE = "DEV-OWNER-INSTALL"
  ACTIVATION_STATUS = "activated"
  ACTIVATION_LAST_VERIFIED = "9999999999"
  LICENSE_SERVER_URL = "https://your-license-server.com"
  ```

Then push another trivial commit (or redeliver the webhook again) — this
time `php spark migrate` should succeed and build out the full schema
fresh, since the database is empty. Check `deploy.log` again to
confirm.

### 9. Validate
- Visit `https://tmpm.mfmlekkiphaseone.org/login` — should load
- Check `deploy.log` shows a clean run top to bottom
- Try a couple of admin pages, and the new modules (Marketplace,
  Counseling, etc.) if you want to exercise them

Once staging looks right, you've proven the whole pipeline end to end —
sync, exclusions, composer, migrations — without any risk to the live site.

---

## Production — app.mfmlekkiphaseone.org

Only do this once staging has been running cleanly for a while and you're
intentionally ready to commit to it. This is the actual "go live" step
flagged earlier (unreviewed migrations and licensing changes going out to
real production).

### 1. Find the live document root
cPanel → **Domains** → find `app.mfmlekkiphaseone.org` → copy its
**Document Root** column.

### 2. Clone the repo
cPanel → **Git™ Version Control → Create**.
- Clone URL: `https://github.com/Innovatyou/mfmlekkibackend.git`
- Repository Path: `/home/mfmlbbcm/repositories/mfmadmin`
- Branch: `main` (merge `upgrade/churchbackend-merge` into `main` first,
  if you haven't already — this repo won't have any of the new work on
  `main` until that PR is merged)

### 3. Create the production config
cPanel File Manager → create `/home/mfmlbbcm/deploy-config.php`:

```php
<?php
$secret        = '73bcd32e3c6909e733c95fcff6f18e17fa785d30cac333f77a471d8fe08d907d';
$repoDir       = '/home/mfmlbbcm/repositories/mfmadmin';
$deployDir     = '/home/mfmlbbcm/app';
$branch        = 'main';
$runMigrations = true;
```

(That secret was already generated earlier in this setup — reuse it, or
generate a fresh one; either is fine as long as it matches step 5.)

### 4. Make the webhook reachable
Copy `/home/mfmlbbcm/repositories/mfmadmin/deploy/webhook-production.php`
→ into `/home/mfmlbbcm/app/deploy-hook.php`, unchanged. It's
self-contained (no require of another repo file) — see the staging
section's note on what that does and doesn't future-proof.

Public URL: `https://app.mfmlekkiphaseone.org/deploy-hook.php`

### 5. Add the GitHub webhook
Same as staging, but Payload URL `https://app.mfmlekkiphaseone.org/deploy-hook.php`
and the secret from step 3.

### 6. Dry-run the sync before trusting it — do not skip this
Unlike staging, this server already has real production config and real
user data. Before the webhook ever fires for real, if cPanel's Terminal
is available:

```bash
rsync -a --delete --dry-run \
  --exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' \
  --exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' \
  repositories/mfmadmin/ app/
```

Anything listed under a `deleting` line exists live but isn't in this
repo. Since this server has only ever been deployed manually before,
review that list before letting the real sync delete anything you don't
recognize.

### 7. Test
Push to `main`, check `deploy/deploy.log` in `/home/mfmlbbcm/app/`, check
the webhook delivery shows `200` in GitHub, check the live site still works.

---

## Rolling back (either target)

The webhook doesn't roll back on failure automatically. Via cPanel
Terminal (or ask me to walk through File Manager equivalents if Terminal
isn't available):

```bash
cd /home/mfmlbbcm/repositories/<mfmadmin-or-church-staging>
git reset --hard <last-good-commit>
rsync -a --delete \
  --exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' \
  --exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' \
  ./ /path/to/that/document/root/
php spark migrate --version <corresponding-earlier-migration>  # if needed
```
