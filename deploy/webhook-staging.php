<?php
/**
 * GitHub webhook receiver — deploys to staging (church.mfmlekkiphaseone.org)
 * on push to upgrade/churchbackend-merge. See AUTO_DEPLOY_SETUP.md for setup.
 */
$configPath = getenv('MFMADMIN_STAGING_DEPLOY_CONFIG') ?: '/home/mfmlbbcm/church-deploy-config.php';
require __DIR__ . '/webhook-core.php';
