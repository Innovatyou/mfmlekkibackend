<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixLivestreamStreamUrlColumn extends Migration
{
    public function up()
    {
        // Change tbl_livestreams.link column from INT to VARCHAR(500)
        // This ensures YouTube video IDs and stream URLs are stored as strings, never as integers
        $this->forge->modifyColumn('tbl_livestreams', [
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'comment'    => 'YouTube video ID or stream URL (must be string, never integer)',
            ],
        ]);
    }

    public function down()
    {
        // Revert to original INT(11) if needed
        $this->forge->modifyColumn('tbl_livestreams', [
            'link' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
    }
}
