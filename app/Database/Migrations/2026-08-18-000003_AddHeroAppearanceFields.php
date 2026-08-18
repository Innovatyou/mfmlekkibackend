<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeroAppearanceFields extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('hero_text_color', 'tbl_landing_content')) {
            $this->forge->addColumn('tbl_landing_content', [
                'hero_text_color' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '#ffffff'],
            ]);
        }
        if (!$this->db->fieldExists('hero_overlay_opacity', 'tbl_landing_content')) {
            $this->forge->addColumn('tbl_landing_content', [
                'hero_overlay_opacity' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'default' => 25],
            ]);
        }
    }

    public function down()
    {
        foreach (['hero_text_color', 'hero_overlay_opacity'] as $field) {
            if ($this->db->fieldExists($field, 'tbl_landing_content')) {
                $this->forge->dropColumn('tbl_landing_content', $field);
            }
        }
    }
}
