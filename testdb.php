<?php
// Database connection parameters
$host = 'localhost';
$db   = 'churchappsaas_db';
$user = 'root';
$pass = '';
$port = 3306;

// Create connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully to database: " . $db;

// Optional: Show table list
$result = $conn->query("SHOW TABLES");
if ($result->num_rows > 0) {
    echo "<br><br>Tables in database:<br>";
    while($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
} else {
    echo "<br>No tables found in database.";
}

$conn->close();
