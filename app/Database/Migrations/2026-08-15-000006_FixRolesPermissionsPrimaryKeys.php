<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * tbl_roles.id and tbl_permissions.id were defined without a PRIMARY KEY /
 * AUTO_INCREMENT (a bug in the original CreateRolesAndPermissions migration —
 * Forge's addKey('id', false, true) produces a plain UNIQUE key, not a
 * primary key, and doesn't preserve the auto_increment attribute from
 * addField). Every seeder that relies on insertBatch() to auto-assign ids
 * (RolesAndPermissionsSeeder, PermissionSeeder) silently wrote id=0 for
 * every row instead, and any INSERT through the admin UI (AdminRoles::store)
 * would do the same going forward. This backfills tbl_roles to the ids the
 * rest of the app already hardcodes (SetupController, AddNewModulePermissions
 * migration: 1=super_admin, 2=admin, 3=manager, 4=editor, 5=viewer), clears
 * out the now-untrustworthy tbl_permissions/tbl_role_permissions data, and
 * adds the missing keys so this can't recur.
 */
class FixRolesPermissionsPrimaryKeys extends Migration
{
    private array $roleIds = [
        'super_admin' => 1,
        'admin'       => 2,
        'manager'     => 3,
        'editor'      => 4,
        'viewer'      => 5,
    ];

    public function up()
    {
        foreach ($this->roleIds as $name => $id) {
            $this->db->table('tbl_roles')->where('name', $name)->update(['id' => $id]);
        }

        if (!$this->hasPrimaryKey('tbl_roles')) {
            $this->db->query('ALTER TABLE tbl_roles MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
            $this->db->query('ALTER TABLE tbl_roles AUTO_INCREMENT = ' . (count($this->roleIds) + 1));
        }

        if (!$this->hasPrimaryKey('tbl_permissions')) {
            // Existing rows (if any) collided at id=0 with no reliable name->id
            // mapping to preserve; SetupController::setupPermissions() truncates
            // and rebuilds both tables from a known-good list, so clear them here
            // rather than leave inconsistent duplicate-id rows behind.
            $this->db->table('tbl_role_permissions')->truncate();
            $this->db->table('tbl_permissions')->truncate();
            $this->db->query('ALTER TABLE tbl_permissions MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
        }

        // Point any account seeded before this fix at the correct role.
        $this->db->table('tbl_churches')->where('role', 1)->where('role_id', 0)->update(['role_id' => 1]);
    }

    public function down()
    {
        // Structural fix only; not meaningfully reversible.
    }

    private function hasPrimaryKey(string $table): bool
    {
        $row = $this->db->query(
            "SELECT COUNT(*) AS c FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = 'PRIMARY'",
            [$table]
        )->getRow();
        return $row && (int) $row->c > 0;
    }
}
