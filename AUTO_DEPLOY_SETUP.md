# Auto-deploy setup — app.mfmlekkiphaseone.org

This wires up `.github/workflows/deploy-production.yml` to deploy automatically
on every push to `main`. All of the steps below happen on **your** side (GitHub
web UI + your server) — nothing here can be done remotely without your
credentials.

**Read the whole thing before running anything for real.** The last step
(the first live sync) is the one that can silently delete files if skipped.

---

## 1. Generate a deploy key (on your own machine, not the server)

```bash
ssh-keygen -t ed25519 -C "github-deploy-mfmadmin" -f ./mfmadmin_deploy_key -N ""
```

This creates two files: `mfmadmin_deploy_key` (private) and
`mfmadmin_deploy_key.pub` (public). Don't reuse your personal SSH key.

## 2. Authorize the public key on the server

In cPanel: **Security → SSH Access → Manage SSH Keys → Import Key**, paste the
contents of `mfmadmin_deploy_key.pub`, then **Authorize**.

(If cPanel's SSH Access page isn't visible, the host may need to enable it on
the account first — ask your host if step 2 doesn't show the option.)

## 3. Add the GitHub Secrets

Repo → **Settings → Secrets and variables → Actions → New repository secret**.
Add all of these:

| Secret | Value |
|---|---|
| `PROD_SSH_HOST` | The server's SSH hostname (often the same as the domain, but check cPanel's SSH Access page — it sometimes differs) |
| `PROD_SSH_PORT` | Usually `22`, but cPanel sometimes uses a non-standard port — check the SSH Access page |
| `PROD_SSH_USERNAME` | Your cPanel username |
| `PROD_SSH_PRIVATE_KEY` | The full contents of `mfmadmin_deploy_key` (the *private* key file, not the `.pub` one) |
| `PROD_REPO_DIR` | Where the git clone will live on the server — e.g. `/home/USERNAME/repositories/mfmadmin` (**not** inside `public_html`) |
| `PROD_DEPLOY_DIR` | The live document root for app.mfmlekkiphaseone.org — e.g. `/home/USERNAME/public_html/app.mfmlekkiphaseone.org` (confirm the exact path in cPanel's File Manager) |

Delete `mfmadmin_deploy_key` and `mfmadmin_deploy_key.pub` from your machine
once they're in GitHub Secrets and cPanel — you don't need local copies.

## 4. One-time clone, on the server (SSH in yourself first)

```bash
ssh USERNAME@HOST -p PORT
mkdir -p ~/repositories
git clone https://github.com/Innovatyou/mfmlekkibackend.git ~/repositories/mfmadmin
cd ~/repositories/mfmadmin
git checkout main
```

## 5. Dry-run the sync before trusting it — do this before the first real deploy

Still on the server:

```bash
rsync -a --delete --dry-run \
  --exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' \
  --exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' \
  ~/repositories/mfmadmin/ /path/to/PROD_DEPLOY_DIR/
```

`--dry-run` prints what *would* happen without touching anything. Read the
output carefully — anything listed under a `deleting` line is a file that
exists in the live directory but not in this repo, and `--delete` would
remove it for real. If you see files there you don't recognize or don't want
gone, add another `--exclude` for them before doing this for real.

## 6. First real deploy — do it manually once, not via the pipeline

Once the dry run looks right, run the same command without `--dry-run`, then
`cd` into `PROD_DEPLOY_DIR` and run `composer install` and
`php spark migrate` by hand, watching the output. This is your one chance to
catch a bad migration before it's automatic — the docs already in this repo
(`STEP_BY_STEP_DEPLOYMENT.md`) show what a migration failure looks like and
how to roll one back with `php spark migrate --version <previous>`.

## 7. From here on, it's automatic

Every push to `main` now triggers `.github/workflows/deploy-production.yml`,
which does exactly what you just did by hand in steps 5–6. Watch the first
few automatic runs under the repo's **Actions** tab to build confidence
before trusting it silently.

## Rolling back

The workflow doesn't roll back on failure automatically. If a deploy breaks
production: SSH in, `cd ~/repositories/mfmadmin`, `git reset --hard <last-good-commit>`,
re-run the rsync from step 5 (without `--dry-run`), then `php spark migrate`
against that older code if needed.
