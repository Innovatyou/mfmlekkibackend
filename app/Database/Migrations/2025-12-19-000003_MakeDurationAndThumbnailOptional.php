<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeDurationAndThumbnailOptional extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_media')) {
            return;
        }
        $this->forge->modifyColumn('tbl_media', [
            'duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Video duration in milliseconds (optional)',
            ],
        ]);

        // Make cover_photo nullable in tbl_media
        // Allows videos without custom thumbnail links
        $this->forge->modifyColumn('tbl_media', [
            'cover_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'comment'    => 'Thumbnail image filename or URL (optional)',
            ],
        ]);
    }

    public function down()
    {
        // Revert duration to NOT NULL with default 0
        $this->forge->modifyColumn('tbl_media', [
            'duration' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 0,
            ],
        ]);

        // Revert cover_photo to NOT NULL with default empty string
        $this->forge->modifyColumn('tbl_media', [
            'cover_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => false,
                'default'    => '',
            ],
        ]);
    }
}
