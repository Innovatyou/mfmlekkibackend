<?php

namespace App\Models;

use App\Models\Basemodel;

class Users_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }


  function usersListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $builder->where('role !=', 2);
    $query = $builder->get();
    $result =  $query->getResult();
    return $result;
  }

  function checkEmailExists($email, $id = 0)
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select("email");
    $builder->where("email", $email);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    //var_dump($query->result()); die;
    return $query->getResult();
  }


  function addNewAdmin($info)
  {
    if (empty($this->checkEmailExists($info['email']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_churches');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['new_admin_added'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['email_exist'];
    }
    
    

  }


  function editAdmin($info, $id)
  {
    if (empty($this->checkEmailExists($info['email'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_churches');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['ok'];
      'Admin User edited successfully';
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['email_exist'];
    }
    
    

  }


  function getAdminInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function deleteAdmin($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['admin_del'];
    
    

  }
}
