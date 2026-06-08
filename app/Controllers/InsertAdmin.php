<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class InsertAdmin extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'fullname' => 'Admin User',
            'role' => 1,
            'status' => 0,
            'isdelete' => 1,
            'apitoken' => 'demoapitoken123',
            'never_expire' => 1,
            'date_created' => date('Y-m-d H:i:s'),
        ];

        $builder = $db->table('tbl_churches');

        if ($builder->insert($data)) {
            echo "Admin user inserted successfully!";
        } else {
            $error = $db->error();
            echo "Error inserting admin: " . $error['message'];
        }

        // Optional: display all rows
        $query = $builder->get();
        print_r($query->getResult());
    }
}
