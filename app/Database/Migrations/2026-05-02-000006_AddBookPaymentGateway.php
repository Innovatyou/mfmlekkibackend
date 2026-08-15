<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookPaymentGateway extends Migration
{
    public function up()
    {
        $cols = $this->db->getFieldNames('settings');
        if (!in_array('book_payment_gateway', $cols)) {
            $this->db->query(
                "ALTER TABLE settings ADD COLUMN book_payment_gateway VARCHAR(20) NOT NULL DEFAULT 'paystack'"
            );
        }
    }

    public function down()
    {
        $cols = $this->db->getFieldNames('settings');
        if (in_array('book_payment_gateway', $cols)) {
            $this->db->query("ALTER TABLE settings DROP COLUMN book_payment_gateway");
        }
    }
}
