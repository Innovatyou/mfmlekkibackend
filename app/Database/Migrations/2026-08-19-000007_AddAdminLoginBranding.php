<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminLoginBranding extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('settings')) return;
        $fields = $this->db->getFieldNames('settings');
        $columns = [];
        if (!in_array('admin_login_message', $fields, true)) {
            $columns['admin_login_message'] = ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Welcome back! Manage your congregation, events, and community from one place.'];
        }
        if (!in_array('admin_login_badges', $fields, true)) {
            $columns['admin_login_badges'] = ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Members,Events,Finance,Reports'];
        }
        if ($columns) $this->forge->addColumn('settings', $columns);
    }

    public function down()
    {
        foreach (['admin_login_message', 'admin_login_badges'] as $field) {
            if ($this->db->fieldExists($field, 'settings')) $this->forge->dropColumn('settings', $field);
        }
    }
}
