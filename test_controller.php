<?php
// Test if the AdminRoles controller can be instantiated
require 'vendor/autoload.php';

try {
    $controller = new \App\Controllers\AdminRoles();
    echo "✓ AdminRoles controller instantiated successfully\n";
    
    // Check if hasPermission is available
    if (function_exists('hasPermission')) {
        echo "✓ hasPermission() function is available\n";
    } else {
        echo "✗ hasPermission() function is NOT available\n";
    }
    
    // Check if hasRole is available
    if (function_exists('hasRole')) {
        echo "✓ hasRole() function is available\n";
    } else {
        echo "✗ hasRole() function is NOT available\n";
    }
    
    // Check if isSuperAdmin is available
    if (function_exists('isSuperAdmin')) {
        echo "✓ isSuperAdmin() function is available\n";
    } else {
        echo "✗ isSuperAdmin() function is NOT available\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
