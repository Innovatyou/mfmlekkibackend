<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCounselingSystem extends Migration
{
    public function up()
    {
        // Main counseling cases
        if (!$this->db->tableExists('tbl_counseling_cases')) {
            $this->forge->addField([
                'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'member_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'member_name'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'member_email'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'member_phone'    => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => ''],
                'category'        => ['type' => 'ENUM', 'constraint' => ['marriage', 'family', 'grief', 'addiction', 'mental_health', 'financial', 'spiritual', 'relationship', 'other'], 'default' => 'other'],
                'title'           => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'status'          => ['type' => 'ENUM', 'constraint' => ['open', 'in_progress', 'on_hold', 'closed', 'referred'], 'default' => 'open'],
                'priority'        => ['type' => 'ENUM', 'constraint' => ['low', 'normal', 'high', 'urgent'], 'default' => 'normal'],
                'assigned_to'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'is_confidential' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'opened_by'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'opened_at'       => ['type' => 'DATETIME', 'null' => true],
                'closed_at'       => ['type' => 'DATETIME', 'null' => true],
                'next_followup'   => ['type' => 'DATE', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('member_id');
            $this->forge->addKey('status');
            $this->forge->createTable('tbl_counseling_cases');
        }

        // Session logs per case
        if (!$this->db->tableExists('tbl_counseling_sessions')) {
            $this->forge->addField([
                'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'case_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'session_type'     => ['type' => 'ENUM', 'constraint' => ['in_person', 'phone', 'video', 'email', 'prayer', 'other'], 'default' => 'in_person'],
                'session_date'     => ['type' => 'DATE', 'null' => true],
                'duration_minutes' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
                'notes'            => ['type' => 'TEXT'],
                'outcome'          => ['type' => 'TEXT', 'null' => true],
                'next_steps'       => ['type' => 'TEXT', 'null' => true],
                'logged_by'        => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'created_at'       => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('case_id');
            $this->forge->createTable('tbl_counseling_sessions');
        }

        // Follow-up reminders
        if (!$this->db->tableExists('tbl_counseling_reminders')) {
            $this->forge->addField([
                'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'case_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'reminder_date' => ['type' => 'DATE', 'null' => true],
                'note'          => ['type' => 'TEXT', 'null' => true],
                'is_done'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'created_by'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'created_at'    => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('case_id');
            $this->forge->createTable('tbl_counseling_reminders');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_counseling_reminders', true);
        $this->forge->dropTable('tbl_counseling_sessions', true);
        $this->forge->dropTable('tbl_counseling_cases', true);
    }
}
