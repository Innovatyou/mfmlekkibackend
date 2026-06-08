<?php
$mysqli = new mysqli("localhost", "root", "", "mfmdatabase");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== tbl_churches Table Structure ===\n";
$result = $mysqli->query("DESCRIBE tbl_churches");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")" . ($row['Null'] === 'NO' ? " NOT NULL" : " NULL") . "\n";
}

$mysqli->close();
?>
