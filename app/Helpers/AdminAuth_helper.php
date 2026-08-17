<?php

/**
 * Admin Authorization Helper
 * 
 * Provides functions for role-based access control
 */

use App\Models\Permission;
use App\Models\Role;

/**
 * Check if current user has a specific role
 *
 * @param string|array $roles Role name(s) to check
 * @return bool
 */
function hasRole($roles)
{
    $session = session();
    $userRole = $session->get('role');

    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }

    return $userRole === $roles;
}

/**
 * Check if current user has specific permission
 *
 * @param string $permission Permission name to check
 * @return bool
 */
function hasPermission($permission)
{
    $session = session();
    $roleId = $session->get('roleId');

    if (!$roleId) {
        return false;
    }

    // Load permission configuration (fallback)
    $config = config('Permissions');
    
    // First check configuration-based permissions
    if (isset($config[$roleId]) && in_array($permission, $config[$roleId])) {
        return true;
    }

    // Then check database for permissions (if the RBAC tables exist)
    try {
        $db = \Config\Database::connect();
        $result = $db->table('tbl_role_permissions')
            ->select('tbl_permissions.*')
            ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
            ->where('tbl_role_permissions.role_id', $roleId)
            ->where('tbl_permissions.name', $permission)
            ->get()
            ->getRow();

        return $result !== null;
    } catch (\Throwable $e) {
        // RBAC tables not yet migrated — deny DB-based permissions
        return false;
    }
}

/**
 * Check if current user has any of the given permissions
 *
 * @param array $permissions Array of permission names
 * @return bool
 */
function hasAnyPermission($permissions)
{
    foreach ($permissions as $permission) {
        if (hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

/**
 * Check if current user has all of the given permissions
 *
 * @param array $permissions Array of permission names
 * @return bool
 */
function hasAllPermissions($permissions)
{
    foreach ($permissions as $permission) {
        if (!hasPermission($permission)) {
            return false;
        }
    }
    return true;
}

/**
 * Get all permissions for current user
 *
 * @return array
 */
function getCurrentUserPermissions()
{
    $session = session();
    $roleId = $session->get('roleId');

    if (!$roleId) {
        return [];
    }

    $permissionModel = new Permission();
    return $permissionModel->getPermissionsForRole($roleId);
}

/**
 * Get all roles
 *
 * @return array
 */
function getAllRoles()
{
    $roleModel = new Role();
    return $roleModel->findAll();
}

/**
 * Get permissions for a specific role
 *
 * @param int $roleId
 * @return array
 */
function getRolePermissions($roleId)
{
    $roleModel = new Role();
    return $roleModel->getPermissions($roleId);
}

/**
 * Get role by ID
 *
 * @param int $roleId
 * @return object|null
 */
function getRole($roleId)
{
    $roleModel = new Role();
    return $roleModel->find($roleId);
}

/**
 * Get permission by name
 *
 * @param string $permissionName
 * @return object|null
 */
function getPermissionByName($permissionName)
{
    $permissionModel = new Permission();
    return $permissionModel->getByName($permissionName);
}

/**
 * Get all permissions grouped by module
 *
 * @return array
 */
function getAllPermissionsByModule()
{
    $permissionModel = new Permission();
    return $permissionModel->getPermissionsByModule();
}

/**
 * Check if user is super admin.
 *
 * Three-layer check to handle all account creation paths:
 *  1. roleId = 1  (new RBAC system, session set at login)
 *  2. role = 1    (legacy integer stored by SuperAdminSeeder)
 *  3. role name   (string like 'Super Admin' / 'super_admin')
 *
 * @return bool
 */
function isSuperAdmin()
{
    // Layer 1: new RBAC numeric role ID
    if ((int) session()->get('roleId') === 1) {
        return true;
    }

    $role = session()->get('role');

    // Layer 2: legacy integer role (SuperAdminSeeder stores role = 1)
    if (is_numeric($role) && (int) $role === 1) {
        return true;
    }

    // Layer 3: string role name
    $roleName = strtolower(trim((string) $role));
    $roleName  = str_replace([' ', '-'], '_', $roleName);
    return in_array($roleName, ['super_admin', 'superadmin'], true);
}

/**
 * Check if user is admin or higher
 *
 * @return bool
 */
function isAdmin()
{
    if (isSuperAdmin()) {
        return true;
    }
    $roleId = (int) session()->get('roleId');
    return $roleId === 2;
}

/**
 * Get current user role ID
 *
 * @return int|null
 */
function getCurrentUserRoleId()
{
    return session()->get('roleId');
}

/**
 * Get current user role name
 *
 * @return string|null
 */
function getCurrentUserRoleName()
{
    return session()->get('role');
}
