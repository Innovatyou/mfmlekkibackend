<?php
/**
 * Run this file ONCE to create the database.
 * Delete it from your server immediately after.
 */
require __DIR__ . '/config.php';

$dataDir = dirname(DB_PATH);
if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0750, true)) {
        die('ERROR: Could not create data directory: ' . $dataDir);
    }
}

if (!is_writable($dataDir)) {
    die('ERROR: Directory is not writable: ' . $dataDir);
}

try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS licenses (
            id             INTEGER PRIMARY KEY AUTOINCREMENT,
            code           VARCHAR(20)  UNIQUE NOT NULL,
            domain         VARCHAR(255) DEFAULT NULL,
            buyer_email    VARCHAR(255) DEFAULT NULL,
            order_ref      VARCHAR(255) DEFAULT NULL,
            activated_at   DATETIME     DEFAULT NULL,
            last_verified  DATETIME     DEFAULT NULL,
            revoked        INTEGER      DEFAULT 0,
            created_at     DATETIME     DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo '<h2 style="color:green">Installation complete!</h2>';
    echo '<p>Database created at: <code>' . htmlspecialchars(DB_PATH) . '</code></p>';
    echo '<p style="color:red"><strong>IMPORTANT: Delete this file (install.php) from your server now!</strong></p>';
    echo '<p><a href="index.php">Go to Admin Panel</a></p>';
} catch (Exception $e) {
    echo '<h2 style="color:red">Error</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
