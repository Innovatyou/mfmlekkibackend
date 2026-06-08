<?php

namespace App\Controllers;

use App\Models\UserRBAC;
use App\Models\Role;

class AdminUsers extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $session;

    public function __construct()
    {
        helper(['form', 'url', 'AdminAuth']);
        $this->userModel = new UserRBAC();
        $this->roleModel = new Role();
        $this->session = session();
    }

    /**
     * List all admin users
     */
    public function index()
    {
        if (!hasPermission('users.view') && !isSuperAdmin()) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $users = $this->userModel->getAllUsersWithRoles();

        return $this->view('admin/users/index', [
            'users' => $users
        ]);
    }

    /**
     * Create new admin user form
     */
    public function create()
    {
        if (!hasPermission('users.create') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to create users');
        }

        $roles = $this->roleModel->findAll();

        return $this->view('admin/users/create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store new admin user
     */
    public function store()
    {
        if (!hasPermission('users.create') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to create users');
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'fullname' => $this->request->getPost('fullname'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status') ?? 0,
            'isdelete' => 1,
        ];

        if ($this->userModel->createAdminUser($data)) {
            return redirect()->to('admin/users')->with('success', 'Admin user created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    /**
     * Edit admin user form
     */
    public function edit($userId)
    {
        if (!hasPermission('users.edit') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to edit users');
        }

        $user = $this->userModel->getUserWithRole($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $roles = $this->roleModel->findAll();

        return $this->view('admin/users/edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update admin user
     */
    public function update($userId)
    {
        if (!hasPermission('users.edit') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to edit users');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status') ?? 0,
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->updateAdminUser($userId, $data)) {
            return redirect()->to('admin/users')->with('success', 'Admin user updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    /**
     * Delete admin user
     */
    public function delete($userId)
    {
        if (!hasPermission('users.delete') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to delete users');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Prevent deletion of current user
        if ($user->email === $this->session->get('userId')) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }

        if ($this->userModel->delete($userId)) {
            return redirect()->to('admin/users')->with('success', 'Admin user deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete user');
    }

    /**
     * Assign role to user
     */
    public function assignRole($userId)
    {
        if (!hasPermission('users.edit') && !isSuperAdmin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access Denied'], 403);
        }

        $roleId = $this->request->getPost('role_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($this->userModel->assignRoleToUser($userId, $roleId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Role assigned successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to assign role'], 400);
    }

    /**
     * View user details
     */
    public function details($userId)
    {
        if (!hasPermission('users.view') && !isSuperAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to view users');
        }

        $user = $this->userModel->getUserWithFullData($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return $this->view('admin/users/view', [
            'user' => $user
        ]);
    }
}
