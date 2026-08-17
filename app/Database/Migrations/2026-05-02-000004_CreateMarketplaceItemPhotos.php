<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMarketplaceItemPhotos extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tbl_marketplace_item_photos (
                id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                item_id    INT UNSIGNED  NOT NULL,
                filename   VARCHAR(255)  NOT NULL,
                sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_item (item_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS tbl_marketplace_item_photos');
    }
}
