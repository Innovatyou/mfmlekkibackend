<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up()
    {
        // tbl_branches
        if (!$this->db->tableExists('tbl_branches')) {
            $this->forge->addField([
                'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'phone'     => ['type' => 'VARCHAR', 'constraint' => 50,  'default' => ''],
                'email'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'address'   => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'pastor'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'latitude'  => ['type' => 'VARCHAR', 'constraint' => 50,  'default' => ''],
                'longitude' => ['type' => 'VARCHAR', 'constraint' => 50,  'default' => ''],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_branches');
        }

        // tbl_media  (audio + video rows share this table, distinguished by 'type')
        if (!$this->db->tableExists('tbl_media')) {
            $this->forge->addField([
                'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'type'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'audio'],  // 'audio' | 'video'
                'video_type'   => ['type' => 'VARCHAR', 'constraint' => 50,  'null' => true],       // e.g. 'youtube_video'
                'category'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'sub_category' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'title'        => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
                'description'  => ['type' => 'TEXT',    'null' => true],
                'source'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'cover_photo'  => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'duration'     => ['type' => 'INT',     'constraint' => 11,  'null' => true],
                'is_free'      => ['type' => 'INT',     'constraint' => 1,   'default' => 1],
                'link'         => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'dateInserted' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_media');
        }

        // tbl_donations
        if (!$this->db->tableExists('tbl_donations')) {
            $this->forge->addField([
                'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'email'     => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'name'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'reason'    => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'reference' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'amount'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
                'method'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'day'       => ['type' => 'INT', 'constraint' => 2,  'null' => true],
                'month'     => ['type' => 'INT', 'constraint' => 2,  'null' => true],
                'year'      => ['type' => 'INT', 'constraint' => 4,  'null' => true],
                'date'      => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->createTable('tbl_donations');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_donations', true);
        $this->forge->dropTable('tbl_media', true);
        $this->forge->dropTable('tbl_branches', true);
    }
}
