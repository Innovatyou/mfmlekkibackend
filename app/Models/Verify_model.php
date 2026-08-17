<?php

namespace App\Models;

use App\Models\Basemodel;

class Verify_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function insertData($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_verification');
    $builder->insert($info);
  }

  //check if verification details exists, when user clicks on the link sent to mail
  public function checkActivationDetails($activation_id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_verification');
    $builder->where('activation_id', $activation_id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  //delete details when user have been verified
  public function deleteActivationDetails($activation_id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_verification');
    $builder->where('activation_id', $activation_id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
  }
}
