<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Define Roles
        $roles = [
            [
                'name'           => 'super_admin',
                'display_name'   => 'Super Administrator',
                'description'    => 'Full access to all system functions',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'admin',
                'display_name'   => 'Administrator',
                'description'    => 'Administrative access with limited control',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'manager',
                'display_name'   => 'Manager',
                'description'    => 'Can manage content and users',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'editor',
                'display_name'   => 'Editor',
                'description'    => 'Can create and edit content',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'viewer',
                'display_name'   => 'Viewer',
                'description'    => 'Read-only access',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $builder = $db->table('tbl_roles');
        $builder->insertBatch($roles);

        // Define Permissions
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View user list', 'module' => 'users', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new users', 'module' => 'users', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Edit existing users', 'module' => 'users', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete users', 'module' => 'users', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Articles Management
            ['name' => 'articles.view', 'display_name' => 'View Articles', 'description' => 'View article list', 'module' => 'articles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'articles.create', 'display_name' => 'Create Articles', 'description' => 'Create new articles', 'module' => 'articles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'articles.edit', 'display_name' => 'Edit Articles', 'description' => 'Edit existing articles', 'module' => 'articles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'articles.delete', 'display_name' => 'Delete Articles', 'description' => 'Delete articles', 'module' => 'articles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Videos Management
            ['name' => 'videos.view', 'display_name' => 'View Videos', 'description' => 'View video list', 'module' => 'videos', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'videos.create', 'display_name' => 'Create Videos', 'description' => 'Create new videos', 'module' => 'videos', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'videos.edit', 'display_name' => 'Edit Videos', 'description' => 'Edit existing videos', 'module' => 'videos', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'videos.delete', 'display_name' => 'Delete Videos', 'description' => 'Delete videos', 'module' => 'videos', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Livestream Management
            ['name' => 'livestream.view', 'display_name' => 'View Livestreams', 'description' => 'View livestream list', 'module' => 'livestream', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'livestream.create', 'display_name' => 'Create Livestream', 'description' => 'Create new livestreams', 'module' => 'livestream', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'livestream.edit', 'display_name' => 'Edit Livestream', 'description' => 'Edit livestreams', 'module' => 'livestream', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'livestream.delete', 'display_name' => 'Delete Livestream', 'description' => 'Delete livestreams', 'module' => 'livestream', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Settings Management
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'View system settings', 'module' => 'settings', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'description' => 'Edit system settings', 'module' => 'settings', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Roles Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View role list', 'module' => 'roles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'module' => 'roles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Edit existing roles', 'module' => 'roles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete roles', 'module' => 'roles', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $builder = $db->table('tbl_permissions');
        $builder->insertBatch($permissions);

        // Get role and permission IDs for mapping
        $superAdminRole = $db->table('tbl_roles')->where('name', 'super_admin')->get()->getRow();
        $allPermissions = $db->table('tbl_permissions')->get()->getResult();

        // Assign all permissions to super_admin
        $rolePermissions = [];
        foreach ($allPermissions as $permission) {
            $rolePermissions[] = [
                'role_id'       => $superAdminRole->id,
                'permission_id' => $permission->id,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        $builder = $db->table('tbl_role_permissions');
        $builder->insertBatch($rolePermissions);

        // Assign specific permissions to other roles
        $adminRole = $db->table('tbl_roles')->where('name', 'admin')->get()->getRow();
        $managerRole = $db->table('tbl_roles')->where('name', 'manager')->get()->getRow();
        $editorRole = $db->table('tbl_roles')->where('name', 'editor')->get()->getRow();
        $viewerRole = $db->table('tbl_roles')->where('name', 'viewer')->get()->getRow();

        // Admin: All permissions except roles management and settings edit
        $adminPerms = ['users.view', 'users.create', 'users.edit', 'users.delete', 
                       'articles.view', 'articles.create', 'articles.edit', 'articles.delete',
                       'videos.view', 'videos.create', 'videos.edit', 'videos.delete',
                       'livestream.view', 'livestream.create', 'livestream.edit', 'livestream.delete',
                       'settings.view', 'roles.view'];
        
        $this->assignPermissionsToRole($db, $adminRole->id, $adminPerms);

        // Manager: Can manage users, articles, videos, livestreams (no delete)
        $managerPerms = ['users.view', 'users.create', 'users.edit',
                        'articles.view', 'articles.create', 'articles.edit',
                        'videos.view', 'videos.create', 'videos.edit',
                        'livestream.view', 'livestream.create', 'livestream.edit'];
        
        $this->assignPermissionsToRole($db, $managerRole->id, $managerPerms);

        // Editor: Can create and edit content only
        $editorPerms = ['articles.view', 'articles.create', 'articles.edit',
                       'videos.view', 'videos.create', 'videos.edit',
                       'livestream.view'];
        
        $this->assignPermissionsToRole($db, $editorRole->id, $editorPerms);

        // Viewer: View only
        $viewerPerms = ['users.view', 'articles.view', 'videos.view', 'livestream.view', 'settings.view'];
        
        $this->assignPermissionsToRole($db, $viewerRole->id, $viewerPerms);
    }

    private function assignPermissionsToRole($db, $roleId, $permissionNames)
    {
        $permissions = $db->table('tbl_permissions')
            ->whereIn('name', $permissionNames)
            ->get()
            ->getResult();

        $rolePermissions = [];
        foreach ($permissions as $permission) {
            $rolePermissions[] = [
                'role_id'       => $roleId,
                'permission_id' => $permission->id,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        $db->table('tbl_role_permissions')->insertBatch($rolePermissions);
    }
}
