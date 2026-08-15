<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookSaleFields extends Migration
{
    public function up()
    {
        $cols = $this->db->getFieldNames('tbl_books');
        if (!in_array('is_for_sale', $cols)) {
            $this->db->query("ALTER TABLE tbl_books ADD COLUMN is_for_sale TINYINT(1) NOT NULL DEFAULT 0");
        }
        if (!in_array('price', $cols)) {
            $this->db->query("ALTER TABLE tbl_books ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00");
        }
        if (!in_array('currency', $cols)) {
            $this->db->query("ALTER TABLE tbl_books ADD COLUMN currency VARCHAR(3) NOT NULL DEFAULT 'USD'");
        }
    }

    public function down()
    {
        $cols = $this->db->getFieldNames('tbl_books');
        if (in_array('currency', $cols)) {
            $this->db->query("ALTER TABLE tbl_books DROP COLUMN currency");
        }
        if (in_array('price', $cols)) {
            $this->db->query("ALTER TABLE tbl_books DROP COLUMN price");
        }
        if (in_array('is_for_sale', $cols)) {
            $this->db->query("ALTER TABLE tbl_books DROP COLUMN is_for_sale");
        }
    }
}
