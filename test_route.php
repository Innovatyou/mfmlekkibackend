<?php
// Test routing
$requestUri = $_SERVER['REQUEST_URI'];
echo "Request URI: " . $requestUri . "\n";

// Check if admin/roles/view is in the URI
if (strpos($requestUri, 'admin/roles/view') !== false) {
    echo "✓ Route contains 'admin/roles/view'\n";
    preg_match('/admin\/roles\/view\/(\d+)/', $requestUri, $matches);
    if (!empty($matches[1])) {
        echo "✓ Found role ID: " . $matches[1] . "\n";
    }
} else {
    echo "✗ Route does NOT contain 'admin/roles/view'\n";
}
?>
