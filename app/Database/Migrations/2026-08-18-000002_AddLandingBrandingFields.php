<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLandingBrandingFields extends Migration
{
    public function up()
    {
        $fields = [
            'header_logo'   => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'header_text'   => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'favicon_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
            'favicon_text'  => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
        ];

        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'tbl_landing_content')) {
                $this->forge->addColumn('tbl_landing_content', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        foreach (['header_logo', 'header_text', 'favicon_image', 'favicon_text'] as $field) {
            if ($this->db->fieldExists($field, 'tbl_landing_content')) {
                $this->forge->dropColumn('tbl_landing_content', $field);
            }
        }
    }
}
