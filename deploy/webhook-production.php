<?php
/**
 * GitHub webhook receiver — deploys to production (app.mfmlekkiphaseone.org)
 * on push to main. See AUTO_DEPLOY_SETUP.md for setup.
 */
$configPath = getenv('MFMADMIN_DEPLOY_CONFIG') ?: '/home/mfmlbbcm/deploy-config.php';
require __DIR__ . '/webhook-core.php';
