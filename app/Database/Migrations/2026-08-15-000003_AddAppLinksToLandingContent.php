<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAppLinksToLandingContent extends Migration
{
    private array $columns = [
        'web_app_url'          => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => '', 'after' => 'footer_text'],
        'web_app_login_text'   => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Member Login', 'after' => 'web_app_url'],
        'android_app_url'      => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => '', 'after' => 'web_app_login_text'],
        'ios_app_url'          => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => '', 'after' => 'android_app_url'],
        'app_download_title'   => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Get Our App', 'after' => 'ios_app_url'],
        'app_download_subtitle' => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => 'Take church with you wherever you go', 'after' => 'app_download_title'],
        'show_app_download'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'show_signup'],
    ];

    public function up()
    {
        $fields = $this->db->getFieldNames('tbl_landing_content');
        foreach ($this->columns as $name => $def) {
            if (!in_array($name, $fields, true)) {
                $this->forge->addColumn('tbl_landing_content', [$name => $def]);
                $fields[] = $name;
            }
        }
    }

    public function down()
    {
        foreach (array_keys($this->columns) as $name) {
            if ($this->db->fieldExists($name, 'tbl_landing_content')) {
                $this->forge->dropColumn('tbl_landing_content', $name);
            }
        }
    }
}
