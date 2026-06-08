<?php
$mysqli = new mysqli("localhost", "root", "", "churchappsaas_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Checking RBAC Tables ===\n";
$tables = ['tbl_roles', 'tbl_permissions', 'tbl_role_permissions'];
foreach($tables as $table) {
  $result = $mysqli->query("SHOW TABLES LIKE '$table'");
  $exists = $result->num_rows > 0;
  if ($exists) {
    $countResult = $mysqli->query("SELECT COUNT(*) as cnt FROM $table");
    $row = $countResult->fetch_assoc();
    echo "$table: EXISTS (" . $row['cnt'] . " rows)\n";
  } else {
    echo "$table: MISSING\n";
  }
}

$mysqli->close();
?>
