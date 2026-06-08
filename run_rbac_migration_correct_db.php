<?php
$mysqli = new mysqli("localhost", "root", "", "mfmdatabase");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->autocommit(false);

try {
    echo "=== Running RBAC Migration in mfmdatabase ===\n";

    // Create roles table
    $sql = "CREATE TABLE IF NOT EXISTS tbl_roles (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) UNIQUE NOT NULL,
        display_name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at DATETIME,
        updated_at DATETIME
    )";
    $mysqli->query($sql);
    echo "✓ Created tbl_roles\n";

    // Create permissions table
    $sql = "CREATE TABLE IF NOT EXISTS tbl_permissions (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) UNIQUE NOT NULL,
        display_name VARCHAR(100) NOT NULL,
        module VARCHAR(50) NOT NULL,
        created_at DATETIME,
        updated_at DATETIME
    )";
    $mysqli->query($sql);
    echo "✓ Created tbl_permissions\n";

    // Create role_permissions junction table
    $sql = "CREATE TABLE IF NOT EXISTS tbl_role_permissions (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        role_id INT(11) UNSIGNED NOT NULL,
        permission_id INT(11) UNSIGNED NOT NULL,
        created_at DATETIME,
        FOREIGN KEY (role_id) REFERENCES tbl_roles(id) ON DELETE CASCADE,
        FOREIGN KEY (permission_id) REFERENCES tbl_permissions(id) ON DELETE CASCADE,
        UNIQUE KEY unique_role_permission (role_id, permission_id)
    )";
    $mysqli->query($sql);
    echo "✓ Created tbl_role_permissions\n";

    // Add role_id column to tbl_churches if it doesn't exist
    $result = $mysqli->query("SHOW COLUMNS FROM tbl_churches LIKE 'role_id'");
    if ($result->num_rows == 0) {
        $sql = "ALTER TABLE tbl_churches ADD COLUMN role_id INT(11) UNSIGNED AFTER id";
        $mysqli->query($sql);
        echo "✓ Added role_id column to tbl_churches\n";
    } else {
        echo "✓ role_id column already exists in tbl_churches\n";
    }

    $mysqli->commit();
    echo "\n✓ Migration completed successfully!\n";

} catch (Exception $e) {
    $mysqli->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}

$mysqli->close();
?>
