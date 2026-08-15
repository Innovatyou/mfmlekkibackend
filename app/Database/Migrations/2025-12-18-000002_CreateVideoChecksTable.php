<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVideoChecksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'video_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'YouTube video ID',
            ],
            'is_embeddable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'comment' => '1 = embeddable, 0 = not embeddable',
            ],
            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'comment' => 'Reason if not embeddable (e.g., "disabled", "restricted")',
            ],
            'privacy_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'YouTube privacy status (public, unlisted, private)',
            ],
            'content_details' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'comment' => 'JSON encoded content details from YouTube API',
            ],
            'checked_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When the check was last performed',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Record creation timestamp',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'on_update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Record update timestamp',
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addUniqueKey('video_id', 'unique_video_id');
        $this->forge->addKey('created_at', false, false, 'idx_created_at');

        $this->forge->createTable('tbl_video_checks', true);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_video_checks', true);
    }
}
