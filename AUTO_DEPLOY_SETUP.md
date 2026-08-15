# Auto-deploy setup — app.mfmlekkiphaseone.org

SSH turned out not to be network-reachable on this hosting account (timed
out against both the domain and the shared IP `199.188.200.152`, on ports
22 and 2222). So this uses a webhook instead: GitHub calls a small PHP
script (`deploy/webhook.php`) over plain HTTPS on every push, and that
script does the `git pull` + deploy using PHP's `exec()` — no SSH needed.

cPanel account: username `mfmlbbcm`, home directory `/home/mfmlbbcm`.

**Read the whole thing before running anything for real.** The first sync
(step 6) is the one that can silently delete files if skipped.

---

## 1. Find the live document root

cPanel → **Domains**. Find `app.mfmlekkiphaseone.org` in the list and copy
its **Document Root** column exactly — you'll need it in step 3 and 4.

## 2. Set up the git clone via cPanel (no SSH needed)

cPanel → **Git™ Version Control → Create**.

- Clone URL: `https://github.com/Innovatyou/mfmlekkibackend.git`
- Repository Path: `/home/mfmlbbcm/repositories/mfmadmin`
- Branch: `main`

If the repo is private, GitHub will ask for credentials — use a
[fine-grained Personal Access Token](https://github.com/settings/tokens)
scoped to read-only on this one repo, not your real password.

## 3. Create the deploy config (holds the secret — never goes in git)

In cPanel's **File Manager**, create `/home/mfmlbbcm/deploy-config.php`
with this content (copy `deploy/deploy-config.example.php` from the repo
after step 2 and edit it in place, or just paste this directly):

```php
<?php
$secret    = 'PASTE_A_LONG_RANDOM_STRING_HERE';
$repoDir   = '/home/mfmlbbcm/repositories/mfmadmin';
$deployDir = '/home/mfmlbbcm/PASTE_THE_DOCUMENT_ROOT_FROM_STEP_1';
$branch    = 'main';
```

Generate the random secret with cPanel's **Terminal** if available, or ask
me and I'll generate one for you to paste in (it doesn't need to be typed
by hand — any long random string works, it just has to match what you put
in GitHub in step 5).

## 4. Point the webhook script at that config, and make it web-reachable

The webhook script (`deploy/webhook.php` in the repo) already looks for
the config at `/home/mfmlbbcm/deploy-config.php` by default, matching step
3 — no edit needed there.

It needs to be reachable at a URL. Since it lives inside the git clone at
`/home/mfmlbbcm/repositories/mfmadmin/deploy/webhook.php`, which is
**outside** the document root, it isn't web-accessible yet. Simplest fix:
in cPanel's File Manager, create a **symlink** from inside your document
root to it — e.g. if the document root is `/home/mfmlbbcm/public_html/app`:

```
/home/mfmlbbcm/public_html/app/deploy-hook.php  →  /home/mfmlbbcm/repositories/mfmadmin/deploy/webhook.php
```

(cPanel File Manager doesn't always expose "create symlink" directly — if
not, cPanel's Terminal can do `ln -s /home/mfmlbbcm/repositories/mfmadmin/deploy/webhook.php /home/mfmlbbcm/public_html/app/deploy-hook.php`,
or just copy the file there manually and re-copy it after any future
change to `deploy/webhook.php` itself.)

Its public URL will then be something like:
`https://app.mfmlekkiphaseone.org/deploy-hook.php`

## 5. Add the GitHub webhook

Repo → **Settings → Webhooks → Add webhook**.

- Payload URL: the URL from step 4
- Content type: `application/json`
- Secret: the same random string from step 3
- Which events: **Just the push event**
- Active: checked

## 6. Dry-run the sync before trusting it

Before the webhook ever fires for real, check what it *would* do. If
cPanel's Terminal is available, run this from `/home/mfmlbbcm`:

```bash
rsync -a --delete --dry-run \
  --exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' \
  --exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' \
  repositories/mfmadmin/ PASTE_THE_DOCUMENT_ROOT_FROM_STEP_1/
```

`--dry-run` prints what would happen without touching anything. Anything
listed under a `deleting` line exists live but not in this repo — since
this server has only ever been deployed manually before, review that list
carefully before letting the real sync (which the webhook runs
automatically) delete anything you don't recognize.

If Terminal isn't available either, the very first automatic deploy *is*
your first real run — reasonable to accept given this is a
straightforward CodeIgniter app, but check `deploy/deploy.log` (created
next to `webhook.php` after the first run) right after the first push to
main, to confirm it did what you expected.

## 7. Test it

Push a trivial commit to `main` (or use GitHub's webhook page → **Recent
Deliveries → Redeliver** to replay the last push). Check:
- GitHub's webhook delivery log shows a `200` response
- `deploy/deploy.log` on the server shows the commands and their output
- The site still loads correctly

---

## Rolling back

The webhook doesn't roll back on failure automatically. If a deploy
breaks production, via cPanel Terminal (or ask me to walk you through
File Manager equivalents if Terminal isn't available):

```bash
cd /home/mfmlbbcm/repositories/mfmadmin
git reset --hard <last-good-commit>
rsync -a --delete \
  --exclude='.git' --exclude='.env' --exclude='writable' --exclude='uploads' \
  --exclude='app/Config/Database.php' --exclude='app/Config/App.php' --exclude='firebase.json' \
  ./ /path/to/document/root/
php spark migrate --version <corresponding-earlier-migration>  # if needed, in the document root
```
