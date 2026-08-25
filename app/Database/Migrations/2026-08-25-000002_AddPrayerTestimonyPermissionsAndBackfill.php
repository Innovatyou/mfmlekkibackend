<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrayerTestimonyPermissionsAndBackfill extends Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // ── New: dedicated Prayer Requests / Testimonies permissions ───────
        // These used to be lumped under the generic "connect" module
        // (Groups + Prayers + Testimonies shared one on/off switch) and
        // were never actually enforced by either controller. Splitting
        // them out so they're independently assignable on Admin > Roles,
        // now that both pages enforce hasPermission().
        $newPermissions = [
            ['name' => 'prayers.view',    'display_name' => 'View Prayer Requests',    'module' => 'prayers',   'description' => 'View prayer requests'],
            ['name' => 'prayers.edit',    'display_name' => 'Manage Prayer Requests',  'module' => 'prayers',   'description' => 'Create, approve, reply to, and delete prayer requests'],
            ['name' => 'testimony.view',  'display_name' => 'View Testimonies',        'module' => 'testimony', 'description' => 'View testimonies'],
            ['name' => 'testimony.edit',  'display_name' => 'Manage Testimonies',      'module' => 'testimony', 'description' => 'Create, approve, reply to, and delete testimonies'],
            ['name' => 'lists.view',      'display_name' => 'View Email & SMS Lists',  'module' => 'lists',     'description' => 'View mailing/SMS lists and their members'],
            ['name' => 'lists.edit',      'display_name' => 'Manage Email & SMS Lists','module' => 'lists',     'description' => 'Create, edit, and delete mailing/SMS lists and their members'],
        ];

        // ── Backfill: permissions that were added to the seeder/config in
        // code but, being a seeder (not a migration), never actually ran
        // against an already-deployed database via `php spark migrate`.
        // `publications` is included defensively in case that original
        // base seed never reached this install either.
        $backfillPermissions = [
            ['name' => 'partnership.view',   'display_name' => 'View Partnership',         'module' => 'partnership',   'description' => 'View partnership programs and partners'],
            ['name' => 'partnership.edit',   'display_name' => 'Manage Partnership',       'module' => 'partnership',   'description' => 'Create and manage partnership tiers and partners'],
            ['name' => 'landing.view',       'display_name' => 'View Website & Signups',   'module' => 'landing',       'description' => 'View landing page content and member signup requests'],
            ['name' => 'landing.edit',       'display_name' => 'Manage Website & Signups', 'module' => 'landing',       'description' => 'Edit landing page content and approve/reject member signups'],
            ['name' => 'mobileadverts.view', 'display_name' => 'View Mobile Adverts',      'module' => 'mobileadverts', 'description' => 'View mobile app advertisement banners'],
            ['name' => 'mobileadverts.edit', 'display_name' => 'Manage Mobile Adverts',    'module' => 'mobileadverts', 'description' => 'Create, edit, and manage mobile app advertisement banners'],
            ['name' => 'publications.view',  'display_name' => 'View Publications',        'module' => 'publications',  'description' => 'View devotionals, books, and articles'],
            ['name' => 'publications.edit',  'display_name' => 'Manage Publications',      'module' => 'publications',  'description' => 'Create and manage publications'],
        ];

        $existingNames = array_column($this->db->table('tbl_permissions')->select('name')->get()->getResultArray(), 'name');

        foreach (array_merge($newPermissions, $backfillPermissions) as $perm) {
            if (!in_array($perm['name'], $existingNames, true)) {
                $perm['created_at'] = $now;
                $this->db->table('tbl_permissions')->insert($perm);
            }
        }

        $permMap = [];
        foreach ($this->db->table('tbl_permissions')->select('id, name')->get()->getResultArray() as $row) {
            $permMap[$row['name']] = $row['id'];
        }

        // Mirror the exact existing connect.view/connect.edit role spread
        // so splitting prayers/testimony out doesn't change anyone's
        // effective access. Also grants the same default access for the
        // previously-orphaned partnership/landing/mobileadverts perms.
        $roleAssignments = [
            1 => ['prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'lists.view', 'lists.edit',
                  'partnership.view', 'partnership.edit', 'landing.view', 'landing.edit', 'mobileadverts.view', 'mobileadverts.edit'],
            2 => ['prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'lists.view', 'lists.edit',
                  'partnership.view', 'partnership.edit', 'landing.view', 'landing.edit', 'mobileadverts.view', 'mobileadverts.edit'],
            3 => ['prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'lists.view',
                  'partnership.view', 'partnership.edit', 'landing.view'],
            4 => ['prayers.view', 'testimony.view', 'lists.view',
                  'partnership.view', 'landing.view'],
            5 => ['prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit'],
        ];

        foreach ($roleAssignments as $roleId => $permNames) {
            foreach ($permNames as $permName) {
                if (!isset($permMap[$permName])) continue;
                $exists = $this->db->table('tbl_role_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permMap[$permName])
                    ->countAllResults();
                if ($exists === 0) {
                    $this->db->table('tbl_role_permissions')->insert([
                        'role_id'       => $roleId,
                        'permission_id' => $permMap[$permName],
                        'created_at'    => $now,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $names = ['prayers.view', 'prayers.edit', 'testimony.view', 'testimony.edit', 'lists.view', 'lists.edit'];
        $ids   = array_column(
            $this->db->table('tbl_permissions')->whereIn('name', $names)->get()->getResultArray(),
            'id'
        );
        if (!empty($ids)) {
            $this->db->table('tbl_role_permissions')->whereIn('permission_id', $ids)->delete();
            $this->db->table('tbl_permissions')->whereIn('id', $ids)->delete();
        }
        // Intentionally not reverting the partnership/landing/mobileadverts/
        // publications backfill or their role grants -- that's a safety
        // net for pre-existing data, not new state this migration owns.
    }
}
