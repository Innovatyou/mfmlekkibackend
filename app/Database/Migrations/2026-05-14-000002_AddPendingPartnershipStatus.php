<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingPartnershipStatus extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `tbl_partnerships` MODIFY `status` ENUM('pending','active','completed','overdue','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE `tbl_partnerships` SET `status` = 'active' WHERE `status` = 'pending'");
        $db->query("ALTER TABLE `tbl_partnerships` MODIFY `status` ENUM('active','completed','overdue','cancelled') NOT NULL DEFAULT 'active'");
    }
}
