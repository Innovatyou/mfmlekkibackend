<?php
/**
 * GitHub webhook receiver — deploys to staging (church.mfmlekkiphaseone.org)
 * on push to upgrade/churchbackend-merge. See AUTO_DEPLOY_SETUP.md for setup.
 */
$configPath = getenv('MFMADMIN_STAGING_DEPLOY_CONFIG') ?: '/home/mfmlbbcm/church-deploy-config.php';
// Absolute path into the repo clone, not __DIR__ — this file gets copied
// out to the document root as deploy-hook.php (outside deploy/), so a
// relative require would look for webhook-core.php next to itself and
// miss it. Pointing at the clone also means every future core-logic fix
// just needs a git push; deploy-hook.php itself never needs to be
// touched again.
require '/home/mfmlbbcm/repositories/church-staging/deploy/webhook-core.php';
