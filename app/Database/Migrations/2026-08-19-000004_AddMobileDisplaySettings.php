<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileDisplaySettings extends Migration
{
    public function up()
    {
        $fields = [
            'mobile_tagline' => ['type' => 'VARCHAR', 'constraint' => 160, 'default' => 'Towards global evangelism'],
            'mobile_header_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#4F46E5'],
            'mobile_chat_background_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#F8F5F8'],
        ];
        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'settings')) $this->forge->addColumn('settings', [$name => $definition]);
        }
    }

    public function down()
    {
        foreach (['mobile_tagline', 'mobile_header_color', 'mobile_chat_background_color'] as $name) {
            if ($this->db->fieldExists($name, 'settings')) $this->forge->dropColumn('settings', $name);
        }
    }
}
