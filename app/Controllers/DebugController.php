<?php

namespace App\Controllers;

use App\Models\Permission;
use App\Models\Role;

class DebugController extends BaseController
{
    public function permissions()
    {
        $permissionModel = new Permission();
        
        echo "<h2>Permissions</h2>";
        $permissions = $permissionModel->findAll();
        foreach($permissions as $p) {
            echo $p->id . " - " . $p->name . " - " . $p->module . "<br>";
        }
        
        echo "<h2>Roles</h2>";
        $roleModel = new Role();
        $roles = $roleModel->findAll();
        foreach($roles as $r) {
            echo $r->id . " - " . $r->name . " - " . $r->display_name . "<br>";
        }
        
        echo "<h2>Role Permissions</h2>";
        $db = \Config\Database::connect();
        $result = $db->table('tbl_role_permissions')
            ->select('tbl_role_permissions.*, tbl_permissions.name as perm_name, tbl_roles.display_name as role_name')
            ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
            ->join('tbl_roles', 'tbl_roles.id = tbl_role_permissions.role_id')
            ->get()
            ->getResult();
        
        foreach($result as $rp) {
            echo "Role: " . $rp->role_name . " - Permission: " . $rp->perm_name . "<br>";
        }
        
        echo "<h2>Current User Session</h2>";
        $session = session();
        echo "userId: " . $session->get('userId') . "<br>";
        echo "roleId: " . $session->get('roleId') . "<br>";
        echo "name: " . $session->get('name') . "<br>";
        echo "status: " . $session->get('status') . "<br>";
        
        echo "<h2>Current User Permissions</h2>";
        if ($session->get('roleId')) {
            $userPerms = $permissionModel->getPermissionsForRole($session->get('roleId'));
            foreach($userPerms as $p) {
                echo $p->name . "<br>";
            }
        }
    }
}
