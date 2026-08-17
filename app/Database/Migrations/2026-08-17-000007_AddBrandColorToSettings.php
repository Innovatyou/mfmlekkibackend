<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBrandColorToSettings extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('settings')) {
            return;
        }

        $fields = $this->db->getFieldNames('settings');
        if (!in_array('brand_color', $fields, true)) {
            $this->forge->addColumn('settings', [
                'brand_color' => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#6366f1'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('settings') && $this->db->fieldExists('brand_color', 'settings')) {
            $this->forge->dropColumn('settings', 'brand_color');
        }
    }
}
