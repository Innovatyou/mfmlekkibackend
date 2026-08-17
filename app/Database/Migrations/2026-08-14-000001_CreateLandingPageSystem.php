<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPageSystem extends Migration
{
    public function up()
    {
        // tbl_landing_content — single row, flat columns (mirrors `settings` table pattern)
        if (!$this->db->tableExists('tbl_landing_content')) {
            $this->forge->addField([
                'id'                     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],

                'hero_title'             => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Welcome to Our Church'],
                'hero_subtitle'          => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'A place to belong, believe and become.'],
                'hero_image'             => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'hero_cta_text'          => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Join Us This Sunday'],
                'hero_cta_link'          => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '#service-times'],

                'about_title'            => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'About Us'],
                'about_content'          => ['type' => 'TEXT', 'null' => true],
                'about_image'            => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],

                'service_times_title'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Service Times'],
                'service_times_subtitle' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Come worship with us'],

                'events_title'           => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Upcoming Events'],
                'events_subtitle'        => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => "Stay connected with what's happening"],

                'sermons_title'          => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Latest Sermons'],
                'sermons_subtitle'       => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Catch up on our recent messages'],

                'gallery_title'          => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Gallery'],
                'gallery_subtitle'       => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Moments from our church family'],

                'leadership_title'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Our Leadership'],
                'leadership_subtitle'    => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Meet the team shepherding our church'],

                'contact_title'          => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Get In Touch'],
                'contact_address'        => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'contact_phone'          => ['type' => 'VARCHAR', 'constraint' => 50,  'default' => ''],
                'contact_email'          => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'contact_map_embed'      => ['type' => 'TEXT', 'null' => true],

                'signup_title'           => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Become a Member'],
                'signup_subtitle'        => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => "We'd love to have you join our church family"],

                'footer_text'            => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'primary_color'          => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '#4f46e5'],

                'show_hero'              => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_about'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_service_times'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_events'            => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_sermons'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_gallery'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_leadership'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_contact'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'show_signup'            => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],

                'updated_at'             => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_landing_content');

            $this->db->table('tbl_landing_content')->insert(['id' => 1]);
        }

        // tbl_service_times
        if (!$this->db->tableExists('tbl_service_times')) {
            $this->forge->addField([
                'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'        => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'day_of_week' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => ''],
                'time_label'  => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
                'location'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'description' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'sort_order'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_service_times');
        }

        // tbl_church_leadership
        if (!$this->db->tableExists('tbl_church_leadership')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'role_title' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'bio'        => ['type' => 'TEXT', 'null' => true],
                'photo'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'email'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'status'     => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_church_leadership');
        }

        // tbl_members: track public website signups awaiting admin review
        $fields = $this->db->getFieldNames('tbl_members');
        if (!in_array('signup_status', $fields, true)) {
            $this->forge->addColumn('tbl_members', [
                'signup_status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['pending', 'approved', 'rejected'],
                    'default'    => 'approved',
                    'null'       => false,
                ],
            ]);
        }
        $fields = $this->db->getFieldNames('tbl_members');
        if (!in_array('signup_source', $fields, true)) {
            $this->forge->addColumn('tbl_members', [
                'signup_source' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'admin', 'null' => false],
            ]);
        }

        // tbl_members.id has never had a PRIMARY KEY / AUTO_INCREMENT in this
        // database — every insert (admin "add member" and now the public
        // signup form) omits id and relies on the (non-strict) connection
        // silently defaulting it to 0, so multiple members can collide on
        // id=0. Restore proper identity semantics, but only if it's currently
        // safe to do so (no existing duplicate ids to conflict with a new key).
        $dupes = $this->db->query(
            'SELECT id FROM tbl_members GROUP BY id HAVING COUNT(*) > 1'
        )->getResultArray();
        $hasPrimaryKey = false;
        foreach ($this->db->query('SHOW KEYS FROM tbl_members WHERE Key_name = "PRIMARY"')->getResultArray() as $row) {
            $hasPrimaryKey = true;
        }
        if (!$hasPrimaryKey && empty($dupes)) {
            $this->db->query('ALTER TABLE tbl_members MODIFY id INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
        }

        // Permissions for the new "landing" module
        $now = date('Y-m-d H:i:s');
        $newPermissions = [
            ['name' => 'landing.view', 'display_name' => 'View Website & Signups',   'module' => 'landing', 'description' => 'View landing page content and member signup requests'],
            ['name' => 'landing.edit', 'display_name' => 'Manage Website & Signups', 'module' => 'landing', 'description' => 'Edit landing page content and approve/reject member signups'],
        ];

        foreach ($newPermissions as $perm) {
            $exists = $this->db->table('tbl_permissions')->where('name', $perm['name'])->countAllResults();
            if (!$exists) {
                $this->db->table('tbl_permissions')->insert(array_merge($perm, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        $rows = $this->db->table('tbl_permissions')
            ->whereIn('name', array_column($newPermissions, 'name'))
            ->get()->getResult();

        $permMap = [];
        foreach ($rows as $row) {
            $permMap[$row->name] = $row->id;
        }

        // superadmin + admin get full manage access; editor/viewer roles get view-only
        $roleAssignments = [
            1 => ['landing.view', 'landing.edit'],
            2 => ['landing.view', 'landing.edit'],
            3 => ['landing.view'],
            4 => ['landing.view'],
        ];

        foreach ($roleAssignments as $roleId => $permNames) {
            foreach ($permNames as $permName) {
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

    public function down()
    {
        $names = ['landing.view', 'landing.edit'];
        $ids = $this->db->table('tbl_permissions')->whereIn('name', $names)->get()->getResultArray();
        $permIds = array_column($ids, 'id');
        if (!empty($permIds)) {
            $this->db->table('tbl_role_permissions')->whereIn('permission_id', $permIds)->delete();
            $this->db->table('tbl_permissions')->whereIn('name', $names)->delete();
        }

        if ($this->db->fieldExists('signup_source', 'tbl_members')) {
            $this->forge->dropColumn('tbl_members', 'signup_source');
        }
        if ($this->db->fieldExists('signup_status', 'tbl_members')) {
            $this->forge->dropColumn('tbl_members', 'signup_status');
        }

        $this->forge->dropTable('tbl_church_leadership', true);
        $this->forge->dropTable('tbl_service_times', true);
        $this->forge->dropTable('tbl_landing_content', true);
    }
}
