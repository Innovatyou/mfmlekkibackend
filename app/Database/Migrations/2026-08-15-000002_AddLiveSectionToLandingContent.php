<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLiveSectionToLandingContent extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('tbl_landing_content');

        if (!in_array('live_title', $fields, true)) {
            $this->forge->addColumn('tbl_landing_content', [
                'live_title' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Join Us Live', 'after' => 'sermons_subtitle'],
            ]);
        }
        $fields = $this->db->getFieldNames('tbl_landing_content');
        if (!in_array('live_subtitle', $fields, true)) {
            $this->forge->addColumn('tbl_landing_content', [
                'live_subtitle' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Tune in to our live service, wherever you are', 'after' => 'live_title'],
            ]);
        }
        $fields = $this->db->getFieldNames('tbl_landing_content');
        if (!in_array('live_offline_message', $fields, true)) {
            $this->forge->addColumn('tbl_landing_content', [
                'live_offline_message' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => "We're not live right now — check our service times below and join us then.", 'after' => 'live_subtitle'],
            ]);
        }
        $fields = $this->db->getFieldNames('tbl_landing_content');
        if (!in_array('show_live', $fields, true)) {
            $this->forge->addColumn('tbl_landing_content', [
                'show_live' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'show_sermons'],
            ]);
        }
    }

    public function down()
    {
        foreach (['show_live', 'live_offline_message', 'live_subtitle', 'live_title'] as $col) {
            if ($this->db->fieldExists($col, 'tbl_landing_content')) {
                $this->forge->dropColumn('tbl_landing_content', $col);
            }
        }
    }
}
