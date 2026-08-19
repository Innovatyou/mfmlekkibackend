<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMobileAdverts extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('tbl_mobile_adverts')) return;
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 160, 'default' => ''],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255],
            'link' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tbl_mobile_adverts');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_mobile_adverts', true);
    }
}
