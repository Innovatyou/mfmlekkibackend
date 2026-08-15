<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPartnershipPayments extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_partnership_payments')) {
            $this->forge->addField([
                'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'partnership_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'amount'         => ['type' => 'DECIMAL', 'constraint' => '12,2'],
                'currency'       => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'USD'],
                'method'         => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'manual'],
                'reference'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'notes'          => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'recorded_by'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('partnership_id');
            $this->forge->createTable('tbl_partnership_payments');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_partnership_payments', true);
    }
}
