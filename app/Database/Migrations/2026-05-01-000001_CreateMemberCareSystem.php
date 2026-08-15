<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMemberCareSystem extends Migration
{
    public function up()
    {
        // Care interaction log
        if (!$this->db->tableExists('tbl_member_care_events')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'member_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'event_type' => ['type' => 'ENUM', 'constraint' => ['call', 'visit', 'email', 'prayer', 'message', 'other'], 'default' => 'other'],
                'note'       => ['type' => 'TEXT', 'null' => true],
                'created_by' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('member_id');
            $this->forge->createTable('tbl_member_care_events');
        }

        // Pastoral notes per member
        if (!$this->db->tableExists('tbl_member_care_notes')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'member_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'note'       => ['type' => 'TEXT'],
                'is_private' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'created_by' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('member_id');
            $this->forge->createTable('tbl_member_care_notes');
        }

        // Add date_inserted to tbl_members for new-member tracking
        if ($this->db->tableExists('tbl_members') && !$this->db->fieldExists('date_inserted', 'tbl_members')) {
            $this->forge->addColumn('tbl_members', [
                'date_inserted' => ['type' => 'DATETIME', 'null' => true, 'after' => 'linkedln'],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_member_care_notes', true);
        $this->forge->dropTable('tbl_member_care_events', true);
        if ($this->db->tableExists('tbl_members') && $this->db->fieldExists('date_inserted', 'tbl_members')) {
            $this->forge->dropColumn('tbl_members', 'date_inserted');
        }
    }
}
