<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoSessionsToCounseling extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('tbl_counseling_sessions')) {
            if (!$this->db->fieldExists('meeting_platform', 'tbl_counseling_sessions')) {
                $this->forge->addColumn('tbl_counseling_sessions', [
                    'meeting_platform'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'session_type'],
                    'meeting_link'        => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'meeting_platform'],
                    'meeting_scheduled_at'=> ['type' => 'DATETIME', 'null' => true, 'after' => 'meeting_link'],
                    'meeting_status'      => ['type' => 'ENUM', 'constraint' => ['pending', 'confirmed', 'completed', 'cancelled'], 'null' => true, 'after' => 'meeting_scheduled_at'],
                    'invite_sent'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'meeting_status'],
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('tbl_counseling_sessions')) {
            foreach (['meeting_platform', 'meeting_link', 'meeting_scheduled_at', 'meeting_status', 'invite_sent'] as $col) {
                if ($this->db->fieldExists($col, 'tbl_counseling_sessions')) {
                    $this->forge->dropColumn('tbl_counseling_sessions', $col);
                }
            }
        }
    }
}
