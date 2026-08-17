<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApiTokenToMembers extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('tbl_members');
        if (!in_array('api_token', $fields, true)) {
            $this->forge->addColumn('tbl_members', [
                'api_token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'after' => 'password'],
            ]);
        }
        $this->db->query('CREATE UNIQUE INDEX idx_tbl_members_api_token ON tbl_members (api_token)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX idx_tbl_members_api_token ON tbl_members');
        if ($this->db->fieldExists('api_token', 'tbl_members')) {
            $this->forge->dropColumn('tbl_members', 'api_token');
        }
    }
}
