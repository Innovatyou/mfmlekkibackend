<?php

namespace App\Models;

use CodeIgniter\Model;

class Role extends Model
{
    protected $table            = 'tbl_roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'display_name', 'description'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [
        'name'         => 'required|min_length[3]|max_length[100]',
        'display_name' => 'required|min_length[3]|max_length[100]',
        'description'  => 'permit_empty|string',
    ];

    protected $skipValidation = false;

    /**
     * Get all permissions for a role
     */
    public function getPermissions($roleId)
    {
        return $this->db->table('tbl_role_permissions')
            ->select('tbl_permissions.*')
            ->join('tbl_permissions', 'tbl_permissions.id = tbl_role_permissions.permission_id')
            ->where('tbl_role_permissions.role_id', $roleId)
            ->get()
            ->getResult();
    }

    /**
     * Get permission names for a role
     */
    public function getPermissionNames($roleId)
    {
        $permissions = $this->getPermissions($roleId);
        return array_map(function ($perm) {
            return $perm->name;
        }, $permissions);
    }

    /**
     * Assign permission to role
     */
    public function assignPermission($roleId, $permissionId)
    {
        return $this->db->table('tbl_role_permissions')->insert([
            'role_id'       => $roleId,
            'permission_id' => $permissionId,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Revoke permission from role
     */
    public function revokePermission($roleId, $permissionId)
    {
        return $this->db->table('tbl_role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    /**
     * Assign multiple permissions to role
     */
    public function assignPermissions($roleId, $permissionIds)
    {
        // Clear existing permissions first
        $this->db->table('tbl_role_permissions')
            ->where('role_id', $roleId)
            ->delete();

        $rolePermissions = [];
        foreach ($permissionIds as $permissionId) {
            $rolePermissions[] = [
                'role_id'       => $roleId,
                'permission_id' => $permissionId,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        return $this->db->table('tbl_role_permissions')->insertBatch($rolePermissions);
    }

    /**
     * Get users with this role
     */
    public function getUsers($roleId)
    {
        return $this->db->table('tbl_churches')
            ->where('role_id', $roleId)
            ->get()
            ->getResult();
    }

    /**
     * All roles, with permission_count and user_count computed per role —
     * used by the roles index page's stat cards.
     */
    public function getRolesWithCounts()
    {
        $roles = $this->findAll();
        foreach ($roles as $role) {
            $role->permission_count = $this->db->table('tbl_role_permissions')
                ->where('role_id', $role->id)
                ->countAllResults();
            $role->user_count = $this->db->table('tbl_churches')
                ->where('role_id', $role->id)
                ->countAllResults();
        }
        return $roles;
    }
}
