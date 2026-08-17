<?php
/**
 * License Server Configuration
 * Change all values below before deploying to your server.
 */

// Admin panel password (change this!)
// To generate a new hash: php -r "echo password_hash('your_password', PASSWORD_DEFAULT);"
define('ADMIN_PASSWORD_HASH', password_hash('changeme123', PASSWORD_DEFAULT));

// SQLite database path (make sure the directory is writable)
define('DB_PATH', __DIR__ . '/data/licenses.db');

// Your app/product name (shown in the admin panel)
define('APP_NAME', 'Church Backend License Server');
