<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMobileAppEnabledToSettings extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('mobile_app_enabled', 'settings')) {
            $this->forge->addColumn('settings', [
                'mobile_app_enabled' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'app_login'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('mobile_app_enabled', 'settings')) {
            $this->forge->dropColumn('settings', 'mobile_app_enabled');
        }
    }
}
