<?php
/**
 * Copy this file OUTSIDE the git repo (e.g. to /home/mfmlbbcm/deploy-config.php)
 * and fill in real values. Never commit the filled-in version — it holds
 * your webhook secret. deploy/webhook.php loads it via MFMADMIN_DEPLOY_CONFIG
 * or its own default path.
 */

// A long random string — generate one with, e.g., bin2hex(random_bytes(32))
// run via `php -r "echo bin2hex(random_bytes(32));"`. Must match exactly
// what you enter as the GitHub webhook's secret.
$secret = 'REPLACE_WITH_A_LONG_RANDOM_SECRET';

// Where the git clone lives — NOT inside the live document root.
$repoDir = '/home/mfmlbbcm/repositories/mfmadmin';

// The live document root for app.mfmlekkiphaseone.org (confirm the exact
// path in cPanel: Domains, look up its "Document Root" column).
$deployDir = '/home/mfmlbbcm/REPLACE_WITH_REAL_DOCUMENT_ROOT';

$branch = 'main';
