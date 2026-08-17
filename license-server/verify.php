<?php
/**
 * Public license verification endpoint.
 * Called by the client app to activate or re-verify a license.
 */
require __DIR__ . '/config.php';

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$code     = trim($_POST['code'] ?? '');
$domain   = trim($_POST['domain'] ?? '');
$activate = !empty($_POST['activate']);

if (empty($code) || empty($domain)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

// Sanitize domain - strip port and protocol
$domain = preg_replace('/:\d+$/', '', $domain); // remove port
$domain = preg_replace('#^https?://#', '', $domain); // remove protocol

try {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM licenses WHERE code = ?');
$stmt->execute([$code]);
$license = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$license) {
    echo json_encode(['success' => false, 'message' => 'Invalid purchase code. Please check and try again.']);
    exit;
}

if ($license['revoked']) {
    echo json_encode(['success' => false, 'revoked' => true, 'message' => 'This license has been revoked. Please contact support.']);
    exit;
}

// Not yet activated on any domain
if (empty($license['domain'])) {
    if ($activate) {
        $pdo->prepare('UPDATE licenses SET domain = ?, activated_at = datetime("now"), last_verified = datetime("now") WHERE code = ?')
            ->execute([$domain, $code]);
        echo json_encode(['success' => true, 'message' => 'License activated successfully.']);
    } else {
        // Re-verification call on unactivated code - just confirm it's valid
        echo json_encode(['success' => true, 'message' => 'License valid.']);
    }
    exit;
}

// Already activated - check domain matches
if ($license['domain'] !== $domain) {
    echo json_encode([
        'success' => false,
        'message' => 'This license is already activated on "' . htmlspecialchars($license['domain']) . '". Contact support to transfer your license.',
    ]);
    exit;
}

// Domain matches - update last_verified timestamp
$pdo->prepare('UPDATE licenses SET last_verified = datetime("now") WHERE code = ?')
    ->execute([$code]);

echo json_encode(['success' => true, 'message' => 'License verified.']);
