<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewModulePermissions extends Migration
{
    private array $newPermissions = [
        ['name' => 'counseling.view', 'display_name' => 'View Counseling',    'module' => 'counseling',  'description' => 'View counseling cases and sessions'],
        ['name' => 'counseling.edit', 'display_name' => 'Manage Counseling',  'module' => 'counseling',  'description' => 'Create and manage counseling cases'],
        ['name' => 'membercare.view', 'display_name' => 'View Member Care',   'module' => 'membercare',  'description' => 'View member care profiles and activity'],
        ['name' => 'membercare.edit', 'display_name' => 'Manage Member Care', 'module' => 'membercare',  'description' => 'Log care events and manage member profiles'],
        ['name' => 'marketplace.view','display_name' => 'View Marketplace',   'module' => 'marketplace', 'description' => 'View marketplace listings and inquiries'],
        ['name' => 'marketplace.edit','display_name' => 'Manage Marketplace', 'module' => 'marketplace', 'description' => 'Manage and approve marketplace listings'],
    ];

    // Roles that get full access to new modules (superadmin + admin)
    private array $fullAccessRoles = [1, 2];

    // Roles that get view-only on counseling/membercare, view+edit on marketplace
    private array $editorRoles = [3];

    // Roles that get view-only on everything new
    private array $viewerRoles = [4];

    // Roles that get marketplace view+edit only
    private array $contributorRoles = [5];

    public function up()
    {
        $now = date('Y-m-d H:i:s');

        foreach ($this->newPermissions as $perm) {
            $exists = $this->db->table('tbl_permissions')
                ->where('name', $perm['name'])
                ->countAllResults();

            if (!$exists) {
                $this->db->table('tbl_permissions')->insert(array_merge($perm, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // Build name→id map for inserted permissions
        $rows = $this->db->table('tbl_permissions')
            ->whereIn('name', array_column($this->newPermissions, 'name'))
            ->get()->getResult();

        $permMap = [];
        foreach ($rows as $row) {
            $permMap[$row->name] = $row->id;
        }

        $assignments = [
            'full'        => ['counseling.view', 'counseling.edit', 'membercare.view', 'membercare.edit', 'marketplace.view', 'marketplace.edit'],
            'editor'      => ['counseling.view', 'membercare.view', 'marketplace.view', 'marketplace.edit'],
            'viewer'      => ['counseling.view', 'membercare.view', 'marketplace.view'],
            'contributor' => ['marketplace.view', 'marketplace.edit'],
        ];

        $roleMap = [
            'full'        => $this->fullAccessRoles,
            'editor'      => $this->editorRoles,
            'viewer'      => $this->viewerRoles,
            'contributor' => $this->contributorRoles,
        ];

        foreach ($roleMap as $level => $roleIds) {
            foreach ($roleIds as $roleId) {
                foreach ($assignments[$level] as $permName) {
                    if (!isset($permMap[$permName])) continue;

                    $exists = $this->db->table('tbl_role_permissions')
                        ->where('role_id', $roleId)
                        ->where('permission_id', $permMap[$permName])
                        ->countAllResults();

                    if (!$exists) {
                        $this->db->table('tbl_role_permissions')->insert([
                            'role_id'       => $roleId,
                            'permission_id' => $permMap[$permName],
                            'created_at'    => $now,
                        ]);
                    }
                }
            }
        }
    }

    public function down()
    {
        $names = array_column($this->newPermissions, 'name');

        $ids = $this->db->table('tbl_permissions')
            ->whereIn('name', $names)
            ->get()->getResultArray();

        $permIds = array_column($ids, 'id');

        if (!empty($permIds)) {
            $this->db->table('tbl_role_permissions')->whereIn('permission_id', $permIds)->delete();
            $this->db->table('tbl_permissions')->whereIn('name', $names)->delete();
        }
    }
}
