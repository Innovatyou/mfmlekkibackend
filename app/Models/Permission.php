<?php

namespace App\Models;

use CodeIgniter\Model;

class Permission extends Model
{
    protected $table            = 'tbl_permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'display_name', 'description', 'module'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'         => 'required|min_length[3]|max_length[100]|is_unique[tbl_permissions.name]',
        'display_name' => 'required|min_length[3]|max_length[100]',
        'module'       => 'required|min_length[3]|max_length[50]',
        'description'  => 'permit_empty|string',
    ];

    protected $skipValidation = false;

    /**
     * Get all permissions grouped by module
     */
    public function getPermissionsByModule()
    {
        $permissions = $this->orderBy('module', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($permissions as $permission) {
            if (!isset($grouped[$permission->module])) {
                $grouped[$permission->module] = [];
            }
            $grouped[$permission->module][] = $permission;
        }

        return $grouped;
    }

    /**
     * Get permissions for a role
     */
    public function getPermissionsForRole($roleId)
    {
        return $this->db->table('tbl_role_permissions')
            ->select('tbl_permissions.*')
            ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
            ->where('tbl_role_permissions.role_id', $roleId)
            ->get()
            ->getResult();
    }

    /**
     * Check if a role has a specific permission
     */
    public function hasPermission($roleId, $permissionName)
    {
        $result = $this->db->table('tbl_role_permissions')
            ->select('tbl_permissions.*')
            ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
            ->where('tbl_role_permissions.role_id', $roleId)
            ->where('tbl_permissions.name', $permissionName)
            ->get()
            ->getRow();

        return $result !== null;
    }

    /**
     * Get permission by name
     */
    public function getByName($name)
    {
        return $this->where('name', $name)->get()->getRow();
    }

    /**
     * Get all permissions for a user via their role
     */
    public function getPermissionsForUser($userId)
    {
        $user = $this->db->table('tbl_churches')
            ->where('id', $userId)
            ->get()
            ->getRow();

        if (!$user || !$user->role_id) {
            return [];
        }

        return $this->getPermissionsForRole($user->role_id);
    }

    /**
     * Check if user has a specific permission
     */
    public function userHasPermission($userId, $permissionName)
    {
        $user = $this->db->table('tbl_churches')
            ->where('id', $userId)
            ->get()
            ->getRow();

        if (!$user || !$user->role_id) {
            return false;
        }

        return $this->hasPermission($user->role_id, $permissionName);
    }
}
