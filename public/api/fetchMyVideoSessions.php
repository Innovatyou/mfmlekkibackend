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

$email = trim($_POST['email'] ?? '');

if ($email === '')    { send_error('Email is required.'); }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { send_error('Invalid email address.'); }

$db = db_connect();

try {
    $stmt = $db->prepare(
        "SELECT id, counseling_request_id, meeting_platform, meeting_link,
                meeting_scheduled_at, meeting_status, duration_minutes,
                case_title, assigned_to
         FROM counseling_video_sessions
         WHERE email = ?
           AND meeting_scheduled_at >= NOW()
         ORDER BY meeting_scheduled_at ASC"
    );
    if (!$stmt) { send_error('Failed to prepare statement.'); }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            'id'                    => (int) $row['id'],
            'counseling_request_id' => (int) $row['counseling_request_id'],
            'meeting_platform'      => $row['meeting_platform']     ?? '',
            'meeting_link'          => $row['meeting_link']         ?? '',
            'meeting_scheduled_at'  => $row['meeting_scheduled_at'] ?? '',
            'meeting_status'        => $row['meeting_status']       ?? '',
            'duration_minutes'      => (int) $row['duration_minutes'],
            'case_title'            => $row['case_title']           ?? '',
            'assigned_to'           => $row['assigned_to']          ?? '',
        ];
    }

    $stmt->close();
    $db->close();

    echo json_encode(['status' => 'ok', 'data' => $rows]);
} catch (\Exception $e) {
    $db->close();
    send_error('Failed to fetch sessions. Please try again.');
}
