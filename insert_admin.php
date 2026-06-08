<?php
// insert_admin.php

// Load CodeIgniter bootstrap (adjust path if needed)
require 'app/Config/Database.php';

// Connect to database
$dbConfig = new \Config\Database();
$db = \Config\Database::connect('default');

// Prepare admin data
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT); // securely hash the password
$fullname = 'Admin User';
$role = 1;
$status = 0;
$isdelete = 1;
$apitoken = 'demoapitoken123';
$never_expire = 1;

// Build insert query
$data = [
    'email' => $email,
    'password' => $password,
    'fullname' => $fullname,
    'role' => $role,
    'status' => $status,
    'isdelete' => $isdelete,
    'apitoken' => $apitoken,
    'never_expire' => $never_expire,
    'date_created' => date('Y-m-d H:i:s'),
];

// Insert into tbl_churches
if ($db->table('tbl_churches')->insert($data)) {
    echo "Admin user inserted successfully!\n";
} else {
    $error = $db->error();
    echo "Error inserting admin: " . $error['message'] . "\n";
}

// Verify
$query = $db->table('tbl_churches')->get();
$result = $query->getResult();
echo "Current tbl_churches entries:\n";
print_r($result);
