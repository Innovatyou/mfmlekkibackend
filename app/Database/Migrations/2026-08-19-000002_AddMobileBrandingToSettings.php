<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileBrandingToSettings extends Migration
{
    public function up()
    {
        $fields = [
            'mobile_app_name' => ['type' => 'VARCHAR', 'constraint' => 120, 'default' => ''],
            'mobile_primary_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#6366F1'],
            'mobile_accent_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#F59E0B'],
            'mobile_background_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#F0F2F5'],
            'mobile_logo_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => ''],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'settings')) {
                $this->forge->addColumn('settings', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        foreach (['mobile_app_name', 'mobile_primary_color', 'mobile_accent_color', 'mobile_background_color', 'mobile_logo_url'] as $name) {
            if ($this->db->fieldExists($name, 'settings')) {
                $this->forge->dropColumn('settings', $name);
            }
        }
    }
}
