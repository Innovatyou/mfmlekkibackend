<?php
$mysqli = new mysqli("localhost", "root", "", "churchappsaas_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Assigning Super Admin Role ===\n";

// Assign role_id = 1 (super_admin) to all admin users
$sql = "UPDATE tbl_churches SET role_id = 1 WHERE role_id IS NULL";
$result = $mysqli->query($sql);

if ($result) {
    echo "✓ Updated " . $mysqli->affected_rows . " users with super_admin role\n";
} else {
    echo "✗ Error: " . $mysqli->error . "\n";
}

echo "\n=== Updated Admin Users ===\n";
$result = $mysqli->query("SELECT id, email, fullname, role_id FROM tbl_churches WHERE role_id IS NOT NULL LIMIT 10");

while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Email: {$row['email']}, Name: {$row['fullname']}, Role ID: {$row['role_id']}\n";
}

$mysqli->close();
?>
