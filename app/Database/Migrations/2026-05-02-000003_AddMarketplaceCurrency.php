<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMarketplaceCurrency extends Migration
{
    public function up()
    {
        $cols = $this->db->getFieldNames('settings');
        if (!in_array('marketplace_currency', $cols)) {
            $this->db->query(
                "ALTER TABLE settings ADD COLUMN marketplace_currency VARCHAR(3) NOT NULL DEFAULT 'USD'"
            );
        }
    }

    public function down()
    {
        $cols = $this->db->getFieldNames('settings');
        if (in_array('marketplace_currency', $cols)) {
            $this->db->query("ALTER TABLE settings DROP COLUMN marketplace_currency");
        }
    }
}
