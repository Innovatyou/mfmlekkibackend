<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Basemodel
{
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * This function is used to check the login credentials of the user
     * @param string $email
     * @param string $password
     */
    function authenticate($email, $password)
    {
        $db = \Config\Database::connect("default");

        $builder = $db->table('tbl_churches');
        $builder->where('email', $email);
        $result = $builder->get()->getRow();

        if (!$result) {
            return false;
        }

        if (password_verify($password, $result->password)) {
            return $result;
        }

        return false;
    }

    public function emailExists(string $email): bool
    {
        $db  = \Config\Database::connect('default');
        $row = $db->table('tbl_churches')->where('email', $email)->get()->getRow();
        return $row !== null;
    }

    public function updatePassword(string $email, string $password): void
    {
        $db = \Config\Database::connect('default');
        $db->table('tbl_churches')
           ->where('email', $email)
           ->update(['password' => password_hash($password, PASSWORD_BCRYPT)]);
    }
}
