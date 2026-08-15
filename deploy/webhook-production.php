<?php
/**
 * GitHub webhook receiver — deploys to production (app.mfmlekkiphaseone.org)
 * on push to main. See AUTO_DEPLOY_SETUP.md for setup.
 */
$configPath = getenv('MFMADMIN_DEPLOY_CONFIG') ?: '/home/mfmlbbcm/deploy-config.php';
// Absolute path into the repo clone, not __DIR__ — see webhook-staging.php
// for why. Every future core-logic fix then just needs a git push;
// deploy-hook.php itself never needs to be touched again.
require '/home/mfmlbbcm/repositories/mfmadmin/deploy/webhook-core.php';
