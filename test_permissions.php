<?php
require 'system/bootstrap.php';

$db = \Config\Database::connect();

echo "=== PERMISSIONS ===\n";
$permissions = $db->table('tbl_permissions')->get()->getResult();
foreach($permissions as $p) {
    echo $p->id . " - " . $p->name . " - " . $p->module . "\n";
}

echo "\n=== ROLES ===\n";
$roles = $db->table('tbl_roles')->get()->getResult();
foreach($roles as $r) {
    echo $r->id . " - " . $r->name . " - " . $r->display_name . "\n";
}

echo "\n=== ROLE PERMISSIONS ===\n";
$role_perms = $db->table('tbl_role_permissions')->get()->getResult();
foreach($role_perms as $rp) {
    echo "Role: " . $rp->role_id . " - Permission: " . $rp->permission_id . "\n";
}
