<?php
$mysqli = new mysqli("localhost", "root", "", "churchappsaas_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Table Structure ===\n";
$result = $mysqli->query("DESCRIBE tbl_churches");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n=== Admin Users in tbl_churches ===\n";
$result = $mysqli->query("SELECT id, email, fullname, role_id FROM tbl_churches LIMIT 10");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roleId = $row['role_id'] ?? 'NULL';
        echo "ID: {$row['id']}, Email: {$row['email']}, Name: {$row['fullname']}, Role ID: $roleId\n";
    }
} else {
    echo "No churches found\n";
}

echo "\n=== Available Roles ===\n";
$result = $mysqli->query("SELECT id, name, display_name FROM tbl_roles ORDER BY id");
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Name: {$row['name']}, Display: {$row['display_name']}\n";
}

$mysqli->close();
?>
