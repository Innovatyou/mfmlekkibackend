<?php

namespace App\Controllers;

class SetupController extends BaseController
{
    /**
     * Check if permissions are set up
     */
    public function checkPermissions()
    {
        $db = \Config\Database::connect();
        
        $permCount = $db->table('tbl_permissions')->countAllResults();
        $rolePermCount = $db->table('tbl_role_permissions')->countAllResults();
        
        return $this->response->setJSON([
            'permissions_count' => $permCount,
            'role_permissions_count' => $rolePermCount,
            'needs_setup' => $permCount === 0,
            'message' => $permCount === 0 
                ? 'Permissions need to be set up. Visit /setup/permissions'
                : 'Permissions are already set up',
        ]);
    }

    /**
     * Setup permissions in the database
     */
    public function setupPermissions()
    {
        $db = \Config\Database::connect();
        
        // Clear existing permissions and role permissions
        $db->table('tbl_role_permissions')->truncate();
        $db->table('tbl_permissions')->truncate();

        $permissions = [
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

            // Counseling Module
            ['name' => 'counseling.view', 'display_name' => 'View Counseling', 'module' => 'counseling', 'description' => 'View counseling cases and sessions'],
            ['name' => 'counseling.edit', 'display_name' => 'Manage Counseling', 'module' => 'counseling', 'description' => 'Create and manage counseling cases'],

            // Member Care Module
            ['name' => 'membercare.view', 'display_name' => 'View Member Care', 'module' => 'membercare', 'description' => 'View member care profiles and activity'],
            ['name' => 'membercare.edit', 'display_name' => 'Manage Member Care', 'module' => 'membercare', 'description' => 'Log care events and manage member profiles'],

            // Marketplace Module
            ['name' => 'marketplace.view', 'display_name' => 'View Marketplace', 'module' => 'marketplace', 'description' => 'View marketplace listings and inquiries'],
            ['name' => 'marketplace.edit', 'display_name' => 'Manage Marketplace', 'module' => 'marketplace', 'description' => 'Manage and approve marketplace listings'],

            // Partnership Module
            ['name' => 'partnership.view', 'display_name' => 'View Partnership', 'module' => 'partnership', 'description' => 'View partnership programs and partners'],
            ['name' => 'partnership.edit', 'display_name' => 'Manage Partnership', 'module' => 'partnership', 'description' => 'Create and manage partnership tiers and partners'],

            // Landing / Website Module
            ['name' => 'landing.view', 'display_name' => 'View Website & Signups', 'module' => 'landing', 'description' => 'View landing page content and member signup requests'],
            ['name' => 'landing.edit', 'display_name' => 'Manage Website & Signups', 'module' => 'landing', 'description' => 'Edit landing page content and approve/reject member signups'],

            // Mobile Adverts Module
            ['name' => 'mobileadverts.view', 'display_name' => 'View Mobile Adverts', 'module' => 'mobileadverts', 'description' => 'View mobile app advertisement banners'],
            ['name' => 'mobileadverts.edit', 'display_name' => 'Manage Mobile Adverts', 'module' => 'mobileadverts', 'description' => 'Create, edit, and manage mobile app advertisement banners'],

            // Prayer Requests Module
            ['name' => 'prayers.view', 'display_name' => 'View Prayer Requests', 'module' => 'prayers', 'description' => 'View prayer requests'],
            ['name' => 'prayers.edit', 'display_name' => 'Manage Prayer Requests', 'module' => 'prayers', 'description' => 'Create, approve, reply to, and delete prayer requests'],

            // Testimonies Module
            ['name' => 'testimony.view', 'display_name' => 'View Testimonies', 'module' => 'testimony', 'description' => 'View testimonies'],
            ['name' => 'testimony.edit', 'display_name' => 'Manage Testimonies', 'module' => 'testimony', 'description' => 'Create, approve, reply to, and delete testimonies'],

            // Email & SMS Lists Module
            ['name' => 'lists.view', 'display_name' => 'View Email & SMS Lists', 'module' => 'lists', 'description' => 'View mailing/SMS lists and their members'],
            ['name' => 'lists.edit', 'display_name' => 'Manage Email & SMS Lists', 'module' => 'lists', 'description' => 'Create, edit, and delete mailing/SMS lists and their members'],
        ];

        $db->table('tbl_permissions')->insertBatch($permissions);

        // Get permissions to map IDs
        $allPerms = $db->table('tbl_permissions')->get()->getResult();
        $permMap = [];
        foreach ($allPerms as $p) {
            $permMap[$p->name] = $p->id;
        }

        // Assign permissions to roles
        $rolePermissions = [
            // Super Admin - All permissions (ID: 1)
            1 => ['members.view', 'members.edit', 'donations.view', 'donations.edit', 'media.view', 'media.edit',
                  'publications.view', 'publications.edit', 'connect.view', 'connect.edit',
                  'prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'events.view', 'events.edit',
                  'hymns.view', 'hymns.edit', 'messaging.view', 'messaging.edit', 'lists.view', 'lists.edit', 'locations.view', 'locations.edit',
                  'settings.view', 'settings.edit', 'admin.users.view', 'admin.users.edit', 'admin.roles.view', 'admin.roles.edit',
                  'counseling.view', 'counseling.edit', 'membercare.view', 'membercare.edit',
                  'marketplace.view', 'marketplace.edit',
                  'partnership.view', 'partnership.edit', 'landing.view', 'landing.edit', 'mobileadverts.view', 'mobileadverts.edit'],

            // Admin - All except admin management (ID: 2)
            2 => ['members.view', 'members.edit', 'donations.view', 'donations.edit', 'media.view', 'media.edit',
                  'publications.view', 'publications.edit', 'connect.view', 'connect.edit',
                  'prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'events.view', 'events.edit',
                  'hymns.view', 'hymns.edit', 'messaging.view', 'messaging.edit', 'lists.view', 'lists.edit', 'locations.view', 'locations.edit',
                  'settings.view', 'settings.edit',
                  'counseling.view', 'counseling.edit', 'membercare.view', 'membercare.edit',
                  'marketplace.view', 'marketplace.edit',
                  'partnership.view', 'partnership.edit', 'landing.view', 'landing.edit', 'mobileadverts.view', 'mobileadverts.edit'],

            // Editor - Can view and edit content (ID: 3)
            3 => ['members.view', 'donations.view', 'media.view', 'media.edit', 'publications.view', 'publications.edit',
                  'connect.view', 'connect.edit', 'prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit',
                  'events.view', 'events.edit', 'hymns.view', 'hymns.edit',
                  'messaging.view', 'lists.view', 'locations.view',
                  'counseling.view', 'membercare.view', 'marketplace.view', 'marketplace.edit',
                  'partnership.view', 'partnership.edit', 'landing.view'],

            // Viewer - Can only view (ID: 4)
            4 => ['members.view', 'donations.view', 'media.view', 'publications.view', 'connect.view',
                  'prayers.view', 'testimony.view', 'events.view',
                  'hymns.view', 'messaging.view', 'lists.view', 'locations.view',
                  'counseling.view', 'membercare.view', 'marketplace.view',
                  'partnership.view', 'landing.view'],

            // Contributor - Can view and create content (ID: 5)
            5 => ['media.view', 'media.edit', 'publications.view', 'publications.edit', 'connect.view', 'connect.edit',
                  'prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit',
                  'events.view', 'hymns.view', 'marketplace.view', 'marketplace.edit'],
        ];

        $data = [];
        foreach ($rolePermissions as $roleId => $permNames) {
            foreach ($permNames as $permName) {
                if (isset($permMap[$permName])) {
                    $data[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permMap[$permName],
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        $db->table('tbl_role_permissions')->insertBatch($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Permissions setup completed successfully',
            'permissions_count' => count($permissions),
            'role_permissions_count' => count($data),
        ]);
    }
}
