<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Resolve the super_admin role_id from tbl_roles
        $superAdminRole = $db->table('tbl_roles')->where('name', 'super_admin')->get()->getRow();
        $roleId = $superAdminRole ? $superAdminRole->id : null;

        $email = 'superadmin@mychurchapp.com';

        // Skip if already exists
        $exists = $db->table('tbl_churches')->where('email', $email)->countAllResults();
        if ($exists > 0) {
            echo "Superadmin already exists — skipping.\n";
            return;
        }

        $db->table('tbl_churches')->insert([
            'fullname'     => 'Super Administrator',
            'email'        => $email,
            'password'     => password_hash('Admin@1234', PASSWORD_DEFAULT),
            'role'         => 1,
            'role_id'      => $roleId,
            'status'       => 0,
            'isdelete'     => 0,
            'never_expire' => 1,
            'logo'         => '',
            'language'     => 'en',
            'date_created' => date('Y-m-d H:i:s'),
        ]);

        echo "Superadmin created successfully.\n";
        echo "  Email   : {$email}\n";
        echo "  Password: Admin@1234\n";
    }
}
