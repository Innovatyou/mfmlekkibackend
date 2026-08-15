<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Populates tbl_churches.role_id for existing admin users based on the
 * role name string they had before the RBAC system was introduced.
 * This ensures isSuperAdmin() works correctly for pre-existing accounts.
 *
 * Mapping (case-insensitive):
 *   Super Admin / superadmin / super_admin → role_id = 1
 *   Admin                                  → role_id = 2
 *   Editor                                 → role_id = 3
 *   Viewer                                 → role_id = 4
 *   Contributor                            → role_id = 5
 */
class PopulateChurchRoleIds extends Migration
{
    private array $roleMap = [
        'super_admin' => 1,
        'superadmin'  => 1,
        'super admin' => 1,
        'admin'       => 2,
        'editor'      => 3,
        'viewer'      => 4,
        'contributor' => 5,
    ];

    public function up()
    {
        if (!$this->db->fieldExists('role_id', 'tbl_churches')) {
            return;
        }

        $users = $this->db->table('tbl_churches')
            ->select('id, role, role_id')
            ->get()->getResult();

        foreach ($users as $user) {
            if (!empty($user->role_id)) {
                continue;
            }

            $roleId = null;

            // Legacy numeric role: SuperAdminSeeder stored role = 1 (integer)
            if (is_numeric($user->role) && (int) $user->role === 1) {
                $roleId = 1;
            } else {
                $normalized = strtolower(trim((string) $user->role));
                $normalized = str_replace(['-', '_'], ' ', $normalized);

                foreach ($this->roleMap as $pattern => $id) {
                    $normalizedPattern = str_replace(['-', '_'], ' ', $pattern);
                    if ($normalized === $normalizedPattern) {
                        $roleId = $id;
                        break;
                    }
                }
            }

            if ($roleId !== null) {
                $this->db->table('tbl_churches')
                    ->where('id', $user->id)
                    ->update(['role_id' => $roleId]);
            }
        }
    }

    public function down()
    {
        // Clearing role_id would break RBAC — not reversible safely
    }
}
