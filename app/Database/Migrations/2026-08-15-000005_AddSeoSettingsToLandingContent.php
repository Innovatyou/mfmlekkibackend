<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSeoSettingsToLandingContent extends Migration
{
    private array $columns = [
        'seo_meta_title'               => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '', 'after' => 'app_download_subtitle'],
        'seo_meta_description'         => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => '', 'after' => 'seo_meta_title'],
        'seo_meta_keywords'            => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => '', 'after' => 'seo_meta_description'],
        'seo_og_image'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '', 'after' => 'seo_meta_keywords'],
        'seo_twitter_handle'           => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '', 'after' => 'seo_og_image'],
        'seo_google_site_verification' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '', 'after' => 'seo_twitter_handle'],
        'seo_google_analytics_id'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => '', 'after' => 'seo_google_site_verification'],
        'seo_robots_index'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'seo_google_analytics_id'],
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
