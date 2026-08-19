<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileSurfaceColors extends Migration
{
    public function up()
    {
        $fields = [
            'mobile_surface_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#FFFFFF'],
            'mobile_text_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#0F172A'],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'settings')) $this->forge->addColumn('settings', [$name => $definition]);
        }
    }

    public function down()
    {
        foreach (['mobile_surface_color', 'mobile_text_color'] as $name) {
            if ($this->db->fieldExists($name, 'settings')) $this->forge->dropColumn('settings', $name);
        }
    }
}
