<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Members Module
            ['name' => 'members.view', 'display_name' => 'View Members', 'module' => 'members', 'description' => 'View member list and details'],
            ['name' => 'members.edit', 'display_name' => 'Edit Members', 'module' => 'members', 'description' => 'Edit member information'],
            
            // Donations Module
            ['name' => 'donations.view', 'display_name' => 'View Donations', 'module' => 'donations', 'description' => 'View donation records'],
            ['name' => 'donations.edit', 'display_name' => 'Edit Donations', 'module' => 'donations', 'description' => 'Create and edit donations'],
            
            // Media Module
            ['name' => 'media.view', 'display_name' => 'View Media', 'module' => 'media', 'description' => 'View videos, audios, photos, and livestreams'],
            ['name' => 'media.edit', 'display_name' => 'Manage Media', 'module' => 'media', 'description' => 'Upload and manage media content'],
            
            // Publications Module
            ['name' => 'publications.view', 'display_name' => 'View Publications', 'module' => 'publications', 'description' => 'View devotionals, books, and articles'],
            ['name' => 'publications.edit', 'display_name' => 'Manage Publications', 'module' => 'publications', 'description' => 'Create and manage publications'],
            
            // Connect Module
            ['name' => 'connect.view', 'display_name' => 'View Connect', 'module' => 'connect', 'description' => 'View groups, prayers, and testimonies'],
            ['name' => 'connect.edit', 'display_name' => 'Manage Connect', 'module' => 'connect', 'description' => 'Manage groups, prayers, and testimonies'],
            
            // Events Module
            ['name' => 'events.view', 'display_name' => 'View Events', 'module' => 'events', 'description' => 'View events'],
            ['name' => 'events.edit', 'display_name' => 'Manage Events', 'module' => 'events', 'description' => 'Create and manage events'],
            
            // Hymns Module
            ['name' => 'hymns.view', 'display_name' => 'View Hymns', 'module' => 'hymns', 'description' => 'View hymns'],
            ['name' => 'hymns.edit', 'display_name' => 'Manage Hymns', 'module' => 'hymns', 'description' => 'Create and manage hymns'],
            
            // Messaging Module
            ['name' => 'messaging.view', 'display_name' => 'View Messaging', 'module' => 'messaging', 'description' => 'View messages and inbox'],
            ['name' => 'messaging.edit', 'display_name' => 'Send Messages', 'module' => 'messaging', 'description' => 'Send SMS and email messages'],
            
            // Locations Module
            ['name' => 'locations.view', 'display_name' => 'View Locations', 'module' => 'locations', 'description' => 'View branch locations'],
            ['name' => 'locations.edit', 'display_name' => 'Manage Locations', 'module' => 'locations', 'description' => 'Manage branch locations'],
            
            // Settings Module
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings', 'description' => 'View application settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings', 'description' => 'Edit application settings'],
            
            // Admin Users
            ['name' => 'admin.users.view', 'display_name' => 'View Admin Users', 'module' => 'admin', 'description' => 'View admin users'],
            ['name' => 'admin.users.edit', 'display_name' => 'Manage Admin Users', 'module' => 'admin', 'description' => 'Create and manage admin users'],
            
            // Admin Roles
            ['name' => 'admin.roles.view', 'display_name' => 'View Roles', 'module' => 'admin', 'description' => 'View user roles'],
            ['name' => 'admin.roles.edit', 'display_name' => 'Manage Roles', 'module' => 'admin', 'description' => 'Create and manage roles'],
        ];

        $this->db->table('tbl_permissions')->insertBatch($data);

        // Assign permissions to roles
        $rolePermissions = [
            // Super Admin - All permissions
            1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
            // Admin - All except admin management
            2 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            // Editor - Can view and edit content
            3 => [1, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 17],
            // Viewer - Can only view
            4 => [1, 3, 5, 7, 9, 11, 13, 15, 17],
            // Contributor - Can view and create content
            5 => [1, 5, 6, 7, 8, 9, 10, 11, 12],
        ];

        foreach ($rolePermissions as $roleId => $permissionIds) {
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->db->table('tbl_role_permissions')->insertBatch($data);
        }
    }
}
