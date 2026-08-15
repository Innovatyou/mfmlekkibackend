<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChurchesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('tbl_churches')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'role' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                // 1 = superadmin, 2 = church, 0 = sub-admin
            ],
            'status' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'isdelete' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'never_expire' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'expiry_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'en',
            ],
            'date_created' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->createTable('tbl_churches');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_churches', true);
    }
}
