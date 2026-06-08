<?php
$mysqli = new mysqli("localhost", "root", "", "mfmdatabase");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Admin Users in mfmdatabase ===\n";
$result = $mysqli->query("SELECT id, email, fullname, role_id FROM tbl_churches LIMIT 10");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roleId = $row['role_id'] ?? 'NULL';
        echo "ID: {$row['id']}, Email: {$row['email']}, Name: {$row['fullname']}, Role ID: $roleId\n";
    }
} else {
    echo "No users found\n";
}

echo "\n=== Assigning Super Admin Role ===\n";
$sql = "UPDATE tbl_churches SET role_id = 1 WHERE role_id IS NULL";
$result = $mysqli->query($sql);

if ($result) {
    echo "✓ Updated " . $mysqli->affected_rows . " users with super_admin role\n";
}

echo "\n=== Updated Admin Users ===\n";
$result = $mysqli->query("SELECT id, email, fullname, role_id FROM tbl_churches WHERE role_id IS NOT NULL LIMIT 10");
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Email: {$row['email']}, Name: {$row['fullname']}, Role ID: {$row['role_id']}\n";
}

$mysqli->close();
?>
