<?php
require 'vendor/autoload.php';

$db = config('Database')->connect();

$roles = $db->table('tbl_roles')->get()->getResult();
$perms = $db->table('tbl_permissions')->get()->getResult();
$mappings = $db->table('tbl_role_permissions')->get()->getResult();

echo "=== RBAC Database Verification ===\n\n";
echo "✓ Roles in database: " . count($roles) . "\n";
echo "✓ Permissions in database: " . count($perms) . "\n";
echo "✓ Role-Permission mappings: " . count($mappings) . "\n\n";

echo "Roles:\n";
foreach ($roles as $role) {
    echo "  - " . $role->display_name . " (" . $role->name . ")\n";
}

echo "\nPermissions by Module:\n";
$grouped = [];
foreach ($perms as $perm) {
    if (!isset($grouped[$perm->module])) {
        $grouped[$perm->module] = [];
    }
    $grouped[$perm->module][] = $perm;
}

foreach ($grouped as $module => $permissions) {
    echo "  " . strtoupper($module) . " (" . count($permissions) . " perms)\n";
}

echo "\n=== Setup Complete! ===\n";
