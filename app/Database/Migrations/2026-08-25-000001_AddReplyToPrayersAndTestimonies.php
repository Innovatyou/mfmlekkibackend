<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReplyToPrayersAndTestimonies extends Migration
{
    public function up()
    {
        $prayerFields = $this->db->getFieldNames('tbl_prayers');
        $newPrayerColumns = [];
        if (!in_array('admin_reply', $prayerFields, true)) {
            $newPrayerColumns['admin_reply'] = ['type' => 'TEXT', 'null' => true];
        }
        if (!in_array('replied_at', $prayerFields, true)) {
            $newPrayerColumns['replied_at'] = ['type' => 'DATETIME', 'null' => true];
        }
        if (!in_array('replied_by', $prayerFields, true)) {
            $newPrayerColumns['replied_by'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
        }
        if (!empty($newPrayerColumns)) {
            $this->forge->addColumn('tbl_prayers', $newPrayerColumns);
        }

        // Testimonies also get an `email` column -- unlike prayers, testimony
        // submissions never captured the submitter's email, so a reply has
        // nowhere to push a notification to until the mobile app starts
        // sending one (see Api::submittestimony()).
        $testimonyFields = $this->db->getFieldNames('tbl_testimonies');
        $newTestimonyColumns = [];
        if (!in_array('email', $testimonyFields, true)) {
            $newTestimonyColumns['email'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
        }
        if (!in_array('admin_reply', $testimonyFields, true)) {
            $newTestimonyColumns['admin_reply'] = ['type' => 'TEXT', 'null' => true];
        }
        if (!in_array('replied_at', $testimonyFields, true)) {
            $newTestimonyColumns['replied_at'] = ['type' => 'DATETIME', 'null' => true];
        }
        if (!in_array('replied_by', $testimonyFields, true)) {
            $newTestimonyColumns['replied_by'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
        }
        if (!empty($newTestimonyColumns)) {
            $this->forge->addColumn('tbl_testimonies', $newTestimonyColumns);
        }
    }

    public function down()
    {
        foreach (['admin_reply', 'replied_at', 'replied_by'] as $col) {
            if ($this->db->fieldExists($col, 'tbl_prayers')) {
                $this->forge->dropColumn('tbl_prayers', $col);
            }
        }
        foreach (['email', 'admin_reply', 'replied_at', 'replied_by'] as $col) {
            if ($this->db->fieldExists($col, 'tbl_testimonies')) {
                $this->forge->dropColumn('tbl_testimonies', $col);
            }
        }
    }
}
