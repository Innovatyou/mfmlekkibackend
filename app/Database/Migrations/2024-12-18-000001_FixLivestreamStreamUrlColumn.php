<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixLivestreamStreamUrlColumn extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_livestreams')) {
            return;
        }
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
