<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);

require_once SYSTEMPATH . 'bootstrap.php';

$db = \Config\Database::connect();

// Check if role 2 exists
$role = $db->table('tbl_roles')->where('id', 2)->get()->getRow();
echo "Role 2 (Administrator):\n";
if ($role) {
    echo "  Name: " . $role->name . "\n";
    echo "  Display Name: " . $role->display_name . "\n";
} else {
    echo "  NOT FOUND\n";
}

// Check permissions for role 2
$permissions = $db->table('tbl_role_permissions')
    ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
    ->where('tbl_role_permissions.role_id', 2)
    ->get()
    ->getResult();

echo "\nPermissions for Role 2:\n";
if (!empty($permissions)) {
    foreach ($permissions as $perm) {
        echo "  - " . $perm->display_name . " (" . $perm->name . ")\n";
    }
    echo "\nTotal: " . count($permissions) . " permissions\n";
} else {
    echo "  NO PERMISSIONS ASSIGNED\n";
}

// Check users with role 2
$users = $db->table('tbl_churches')
    ->where('role_id', 2)
    ->get()
    ->getResult();

echo "\nUsers with Role 2:\n";
if (!empty($users)) {
    foreach ($users as $user) {
        echo "  - " . $user->email . "\n";
    }
} else {
    echo "  NO USERS ASSIGNED\n";
}
?>
