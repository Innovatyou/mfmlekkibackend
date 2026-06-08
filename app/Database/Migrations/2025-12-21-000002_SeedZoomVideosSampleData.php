<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedZoomVideosSampleData extends Migration
{
    public function up()
    {
        $data = [
            [
                'title'           => 'SUNDAY NIGHT PRAYER MEETING',
                'meeting_url'     => 'https://us06web.zoom.us/j/4133262470?pwd=ajNyT05YTnhzVWtqa1JEKzhpczQ4dz09',
                'start_time'      => '20:00:00',
                'end_time'        => '22:30:00',
                'privacy_status'  => 'public',
            ]
        ];

        $this->db->table('zoom_videos')->insertBatch($data);
    }

    public function down()
    {
        // Clear the table on rollback
        $this->db->table('zoom_videos')->truncate();
    }
}
