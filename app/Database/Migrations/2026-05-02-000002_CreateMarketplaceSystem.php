<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMarketplaceSystem extends Migration
{
    public function up()
    {
        // Categories
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tbl_marketplace_categories (
                id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name        VARCHAR(100)  NOT NULL,
                description VARCHAR(255)  DEFAULT NULL,
                created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Seed default categories
        $this->db->query("
            INSERT IGNORE INTO tbl_marketplace_categories (name) VALUES
                ('Electronics'),('Clothing & Accessories'),('Books & Media'),
                ('Furniture & Home'),('Kids & Baby'),('Sports & Outdoors'),
                ('Services'),('Free Items'),('Other')
        ");

        // Items
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tbl_marketplace_items (
                id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                church_id    INT UNSIGNED  NOT NULL DEFAULT 0,
                seller_name  VARCHAR(120)  NOT NULL,
                seller_email VARCHAR(160)  NOT NULL,
                seller_phone VARCHAR(30)   DEFAULT NULL,
                category_id  INT UNSIGNED  DEFAULT NULL,
                title        VARCHAR(200)  NOT NULL,
                description  TEXT          DEFAULT NULL,
                price        DECIMAL(10,2) DEFAULT 0.00,
                is_free      TINYINT(1)    NOT NULL DEFAULT 0,
                item_condition VARCHAR(20) NOT NULL DEFAULT 'used',
                image        VARCHAR(255)  DEFAULT NULL,
                location     VARCHAR(150)  DEFAULT NULL,
                status       VARCHAR(20)   NOT NULL DEFAULT 'pending',
                views        INT UNSIGNED  NOT NULL DEFAULT 0,
                is_featured  TINYINT(1)    NOT NULL DEFAULT 0,
                created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at   DATETIME      DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Inquiries / contact on item
        $this->db->query("
            CREATE TABLE IF NOT EXISTS tbl_marketplace_inquiries (
                id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                item_id    INT UNSIGNED NOT NULL,
                name       VARCHAR(120) NOT NULL,
                email      VARCHAR(160) NOT NULL,
                phone      VARCHAR(30)  DEFAULT NULL,
                message    TEXT         NOT NULL,
                is_read    TINYINT(1)   NOT NULL DEFAULT 0,
                created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_item (item_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS tbl_marketplace_inquiries');
        $this->db->query('DROP TABLE IF EXISTS tbl_marketplace_items');
        $this->db->query('DROP TABLE IF EXISTS tbl_marketplace_categories');
    }
}
