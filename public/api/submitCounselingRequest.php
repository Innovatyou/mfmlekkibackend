<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

function send_error($msg) {
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

function db_connect() {
    try {
        $conn = new mysqli('localhost', 'root', '', 'churchbackend');
        if ($conn->connect_error) {
            send_error('Database connection failed.');
        }
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (\Exception $e) {
        send_error('Database connection failed.');
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { send_error('Method not allowed.'); }

$email    = trim($_POST['email']    ?? '');
$name     = trim($_POST['name']     ?? '');
$category = trim($_POST['category'] ?? '');
$title    = trim($_POST['title']    ?? '');
$note     = trim($_POST['note']     ?? '');

if ($email === '')    { send_error('Email is required.'); }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { send_error('Invalid email address.'); }
if ($name === '')     { send_error('Name is required.'); }
if ($category === '') { send_error('Category is required.'); }
if ($title === '')    { send_error('Title is required.'); }

$allowed_categories = [
    'marriage', 'family', 'grief', 'grief_loss', 'addiction',
    'financial', 'spiritual', 'mental_health', 'relationships', 'other',
];
if (!in_array(strtolower($category), $allowed_categories)) {
    send_error('Invalid category.');
}

$db = db_connect();

try {
    $stmt = $db->prepare(
        "INSERT INTO counseling_requests (email, name, category, title, note, status, priority, opened_at, created_at)
         VALUES (?, ?, ?, ?, ?, 'open', 'normal', NOW(), NOW())"
    );
    if (!$stmt) { send_error('Failed to prepare statement.'); }

    $stmt->bind_param('sssss', $email, $name, $category, $title, $note);
    $stmt->execute();
    $stmt->close();
    $db->close();

    echo json_encode([
        'status'  => 'ok',
        'message' => 'Your counseling request has been submitted. A pastor will be in touch soon.',
    ]);
} catch (\Exception $e) {
    $db->close();
    send_error('Failed to submit request. Please try again.');
}
