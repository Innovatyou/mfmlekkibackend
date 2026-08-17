<?php
/**
 * Copy this file OUTSIDE the git repo to /home/mfmlbbcm/church-deploy-config.php
 * and fill in real values. Never commit the filled-in version.
 *
 * This is the STAGING target — a fresh subdomain, so unlike production
 * there's no pre-existing config to protect. You'll still need to create
 * app/Config/Database.php, app/Config/App.php and .env by hand ONCE after
 * the first deploy (see AUTO_DEPLOY_SETUP.md step "Staging: first-time
 * config"), after which they're excluded from every deploy same as prod.
 */

$secret    = 'REPLACE_WITH_A_LONG_RANDOM_SECRET';
$repoDir   = '/home/mfmlbbcm/repositories/church-staging';
$deployDir = '/home/mfmlbbcm/REPLACE_WITH_STAGING_DOCUMENT_ROOT';
$branch    = 'upgrade/churchbackend-merge';

// Staging is exactly where it's safe to let migrations run automatically
// while validating the pipeline — keep this true here even if production
// later gets set to false.
$runMigrations = true;
