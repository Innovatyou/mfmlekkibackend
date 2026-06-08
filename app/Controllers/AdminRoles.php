<?php

namespace App\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\UserRBAC;

class AdminRoles extends BaseController
{
    protected $roleModel;
    protected $permissionModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        helper(['form', 'url', 'AdminAuth']);
        $this->roleModel = new Role();
        $this->permissionModel = new Permission();
        $this->userModel = new UserRBAC();
        $this->session = session();
    }

    /**
     * List all roles
     */
    public function index()
    {
        // Check permission
        if (!hasPermission('roles.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $roles = $this->roleModel->findAll();

        return $this->view('admin/roles/index', [
            'roles' => $roles
        ]);
    }

    /**
     * Create new role form
     */
    public function create()
    {
        if (!hasPermission('roles.create') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to create roles');
        }

        $permissions = $this->permissionModel->getPermissionsByModule();

        return $this->view('admin/roles/create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store new role
     */
    public function store()
    {
        if (!hasPermission('roles.create') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to create roles');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->roleModel->save($data)) {
            $roleId = $this->roleModel->getInsertID();

            // Assign permissions
            $permissionIds = $this->request->getPost('permissions');
            if (!empty($permissionIds)) {
                $this->roleModel->assignPermissions($roleId, $permissionIds);
            }

            return redirect()->to('admin/roles')->with('success', 'Role created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->roleModel->errors());
    }

    /**
     * Edit role form
     */
    public function edit($roleId)
    {
        if (!hasPermission('roles.edit') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to edit roles');
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found');
        }

        $permissions = $this->permissionModel->getPermissionsByModule();
        $rolePermissions = $this->roleModel->getPermissions($roleId);
        $rolePermissionIds = array_map(function ($perm) {
            return $perm->id;
        }, $rolePermissions);

        return $this->view('admin/roles/edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }

    /**
     * Update role
     */
    public function update($roleId)
    {
        if (!hasPermission('roles.edit') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to edit roles');
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found');
        }

        $data = [
            'id' => $roleId,
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
        ];

        // Skip validation for updates to avoid unique constraint on name
        $this->roleModel->skipValidation(true);
        $saved = $this->roleModel->save($data);
        $this->roleModel->skipValidation(false);

        if (!$saved) {
            // Log the error for debugging
            $errors = $this->roleModel->errors();
            log_message('error', 'Role update failed for roleId: ' . $roleId . ' - Errors: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Update permissions
        $permissionIds = $this->request->getPost('permissions');
        if (!empty($permissionIds)) {
            $this->roleModel->assignPermissions($roleId, $permissionIds);
        } else {
            // If no permissions selected, clear all permissions for this role
            $db = \Config\Database::connect();
            $db->table('tbl_role_permissions')
                ->where('role_id', $roleId)
                ->delete();
        }

        return redirect()->to('admin/roles')->with('success', 'Role updated successfully');
    }

    /**
     * Delete role
     */
    public function delete($roleId)
    {
        if (!hasPermission('roles.delete') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to delete roles');
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found');
        }

        // Check if role is assigned to any users
        $usersWithRole = $this->userModel->getUsersByRole($roleId);

        if (!empty($usersWithRole)) {
            return redirect()->back()->with('error', 'Cannot delete role. Users are assigned to this role.');
        }

        if ($this->roleModel->delete($roleId)) {
            // Delete related permissions
            $db = \Config\Database::connect();
            $db->table('tbl_role_permissions')
                ->where('role_id', $roleId)
                ->delete();

            return redirect()->to('admin/roles')->with('success', 'Role deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete role');
    }

    /**
     * View role details
     */
    public function details($roleId)
    {
        if (!hasPermission('roles.view') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to view roles');
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found');
        }

        $permissions = $this->roleModel->getPermissions($roleId);
        $users = $this->userModel->getUsersByRole($roleId);

        return $this->view('admin/roles/view', [
            'role' => $role,
            'permissions' => $permissions,
            'users' => $users
        ]);
    }
}
