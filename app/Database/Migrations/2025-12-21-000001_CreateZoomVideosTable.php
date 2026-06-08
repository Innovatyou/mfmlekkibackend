<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateZoomVideosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'title'       => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'meeting_url' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'start_time'  => [
                'type'    => 'TIME',
                'default' => '20:00:00',
                'null'    => false,
            ],
            'end_time'    => [
                'type' => 'TIME',
                'null' => true,
            ],
            'privacy_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'public',
                'null'       => false,
            ],
            'updated_at'  => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('zoom_videos');
    }

    public function down()
    {
        $this->forge->dropTable('zoom_videos');
    }
}
