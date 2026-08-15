<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRBAC extends Model
{
    protected $table         = 'tbl_churches';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['email', 'password', 'fullname', 'role', 'role_id', 'status', 'isdelete', 'logo', 'never_expire'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'email'      => 'required|valid_email',
        'password'   => 'permit_empty|min_length[6]',
        'fullname'   => 'required|min_length[3]|max_length[100]',
        'role_id'    => 'required|integer',
    ];

    protected $skipValidation = false;

    /**
     * Get user with role details
     */
    public function getUserWithRole($userId)
    {
        return $this->select('tbl_churches.*, tbl_roles.name as role_name, tbl_roles.display_name as role_display_name')
            ->join('tbl_roles', 'tbl_roles.id = tbl_churches.role_id', 'left')
            ->where('tbl_churches.id', $userId)
            ->get()
            ->getRow();
    }

    /**
     * Get user by email with role
     */
    public function getUserByEmailWithRole($email)
    {
        return $this->select('tbl_churches.*, tbl_roles.name as role_name, tbl_roles.display_name as role_display_name')
            ->join('tbl_roles', 'tbl_roles.id = tbl_churches.role_id', 'left')
            ->where('tbl_churches.email', $email)
            ->get()
            ->getRow();
    }

    /**
     * Get all users with their roles
     */
    public function getAllUsersWithRoles()
    {
        return $this->select('tbl_churches.*, tbl_roles.name as role_name, tbl_roles.display_name as role_display_name')
            ->join('tbl_roles', 'tbl_roles.id = tbl_churches.role_id', 'left')
            ->where('tbl_churches.isdelete', 1)
            ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        return $this->select('tbl_churches.*, tbl_roles.name as role_name, tbl_roles.display_name as role_display_name')
            ->join('tbl_roles', 'tbl_roles.id = tbl_churches.role_id', 'left')
            ->where('tbl_churches.role_id', $roleId)
            ->findAll();
    }

    /**
     * Assign role to user
     */
    public function assignRoleToUser($userId, $roleId)
    {
        $role = $this->db->table('tbl_roles')->where('id', $roleId)->get()->getRow();

        if (!$role) {
            return false;
        }

        return $this->update($userId, [
            'role_id' => $roleId,
            'role'    => $role->name
        ]);
    }

    /**
     * Get permissions for a user
     */
    public function getUserPermissions($userId)
    {
        $user = $this->find($userId);

        if (!$user || !$user->role_id) {
            return [];
        }

        $permissionModel = new Permission();
        return $permissionModel->getPermissionsForRole($user->role_id);
    }

    /**
     * Check if user has permission
     */
    public function userHasPermission($userId, $permissionName)
    {
        $user = $this->find($userId);

        if (!$user || !$user->role_id) {
            return false;
        }

        $permissionModel = new Permission();
        return $permissionModel->hasPermission($user->role_id, $permissionName);
    }

    /**
     * Create admin user
     */
    public function createAdminUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Set default values
        if (!isset($data['status'])) {
            $data['status'] = 0;
        }
        if (!isset($data['isdelete'])) {
            $data['isdelete'] = 1;
        }
        if (!isset($data['never_expire'])) {
            $data['never_expire'] = 1;
        }

        // Get role object if role_id is provided
        if (isset($data['role_id'])) {
            $role = $this->db->table('tbl_roles')->where('id', $data['role_id'])->get()->getRow();
            if ($role) {
                $data['role'] = $role->name;
            }
        }

        return $this->insert($data);
    }

    /**
     * Update admin user
     */
    public function updateAdminUser($userId, $data)
    {
        // Hash password only if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        // Update role if role_id is provided
        if (isset($data['role_id'])) {
            $role = $this->db->table('tbl_roles')->where('id', $data['role_id'])->get()->getRow();
            if ($role) {
                $data['role'] = $role->name;
            }
        }

        // Temporarily skip validation for update
        $this->skipValidation(true);
        $result = $this->update($userId, $data);
        $this->skipValidation(false);
        
        return $result;
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials($email, $password)
    {
        $user = $this->getUserWithRole($email);

        if (!$user) {
            return null;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Get user with full role and permission data
     */
    public function getUserWithFullData($userId)
    {
        $user = $this->getUserWithRole($userId);

        if (!$user) {
            return null;
        }

        $permissionModel = new Permission();
        $user->permissions = $permissionModel->getPermissionsForRole($user->role_id);
        $user->permission_names = array_map(function ($perm) {
            return $perm->name;
        }, $user->permissions);

        return $user;
    }
}
