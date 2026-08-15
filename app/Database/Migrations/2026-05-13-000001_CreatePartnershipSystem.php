<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePartnershipSystem extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_partnership_tiers')) {
            $this->forge->addField([
                'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
                'description' => ['type' => 'TEXT', 'null' => true],
                'min_amount'  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
                'color'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '#6366f1'],
                'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
                'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_partnership_tiers');

            // Default tiers
            $this->db->table('tbl_partnership_tiers')->insertBatch([
                ['name' => 'Bronze',   'description' => 'Entry-level partnership',    'min_amount' => 50.00,   'color' => '#b45309', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
                ['name' => 'Silver',   'description' => 'Mid-level partnership',      'min_amount' => 200.00,  'color' => '#475569', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
                ['name' => 'Gold',     'description' => 'Premium partnership',        'min_amount' => 500.00,  'color' => '#b45309', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
                ['name' => 'Diamond',  'description' => 'Elite partnership',          'min_amount' => 1000.00, 'color' => '#6366f1', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ]);
        }

        if (!$this->db->tableExists('tbl_partnerships')) {
            $this->forge->addField([
                'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'member_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'tier_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'partner_name'   => ['type' => 'VARCHAR', 'constraint' => 255],
                'partner_email'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'partner_phone'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'pledge_amount'  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
                'paid_amount'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
                'currency'       => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'USD'],
                'frequency'      => ['type' => 'ENUM', 'constraint' => ['one-time', 'monthly', 'quarterly', 'annually'], 'default' => 'monthly'],
                'start_date'     => ['type' => 'DATE', 'null' => true],
                'end_date'       => ['type' => 'DATE', 'null' => true],
                'status'         => ['type' => 'ENUM', 'constraint' => ['active', 'completed', 'overdue', 'cancelled'], 'default' => 'active'],
                'notes'          => ['type' => 'TEXT', 'null' => true],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
                'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('member_id');
            $this->forge->addKey('tier_id');
            $this->forge->addKey('status');
            $this->forge->createTable('tbl_partnerships');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_partnerships', true);
        $this->forge->dropTable('tbl_partnership_tiers', true);
    }
}
