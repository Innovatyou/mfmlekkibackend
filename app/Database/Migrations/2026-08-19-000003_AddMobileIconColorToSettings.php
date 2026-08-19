<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddMobileIconColorToSettings extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('mobile_icon_color', 'settings')) {
            $this->forge->addColumn('settings', ['mobile_icon_color' => ['type'=>'VARCHAR','constraint'=>7,'default'=>'#FFFFFF']]);
        }
    }
    public function down()
    {
        if ($this->db->fieldExists('mobile_icon_color', 'settings')) $this->forge->dropColumn('settings', 'mobile_icon_color');
    }
}
