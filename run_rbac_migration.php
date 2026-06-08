<?php
$mysqli = new mysqli("localhost", "root", "", "churchappsaas_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->autocommit(false);

try {
    // Create roles table
    $sql1 = "CREATE TABLE IF NOT EXISTS tbl_roles (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        display_name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at DATETIME,
        updated_at DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $mysqli->query($sql1);
    echo "✓ Created tbl_roles\n";

    // Create permissions table
    $sql2 = "CREATE TABLE IF NOT EXISTS tbl_permissions (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        display_name VARCHAR(100) NOT NULL,
        module VARCHAR(50) NOT NULL,
        description TEXT,
        created_at DATETIME,
        updated_at DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $mysqli->query($sql2);
    echo "✓ Created tbl_permissions\n";

    // Create role_permissions junction table
    $sql3 = "CREATE TABLE IF NOT EXISTS tbl_role_permissions (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        role_id INT(11) UNSIGNED NOT NULL,
        permission_id INT(11) UNSIGNED NOT NULL,
        created_at DATETIME,
        UNIQUE KEY unique_role_permission (role_id, permission_id),
        KEY role_id (role_id),
        KEY permission_id (permission_id),
        CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES tbl_roles(id) ON DELETE CASCADE,
        CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES tbl_permissions(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $mysqli->query($sql3);
    echo "✓ Created tbl_role_permissions\n";

    // Add role_id column to tbl_churches if not exists
    $sql4 = "ALTER TABLE tbl_churches ADD COLUMN role_id INT(11) UNSIGNED DEFAULT NULL AFTER id";
    $result = $mysqli->query("SHOW COLUMNS FROM tbl_churches LIKE 'role_id'");
    if ($result->num_rows === 0) {
        $mysqli->query($sql4);
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
