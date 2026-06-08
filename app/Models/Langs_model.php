<?php

namespace App\Models;

use App\Models\Basemodel;

class Langs_model extends Basemodel
{
  protected $db;
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $builder = \Config\Database::connect();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function itemsListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_admin_langs');
    $builder->select('tbl_admin_langs.*');
    $builder->orderBy('id', 'ASC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function checkNameExists($id)
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_admin_langs');
    $builder->select("id");
    $builder->where("id", $id);
    $query = $builder->get();
    //var_dump($query->result()); die;
    return $query->getResult();
  }


  function addnewitem($info)
  {
    if (empty($this->checkNameExists($info['id']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_admin_langs');
      $builder->insert($info);
      $this->status = $locale['ok'];
      $this->message = $locale['add_sucess'];
    } else {
      $this->status = $locale['error'];
      $this->message = $locale['id_exists'];
    }
  }


  function edititem($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_admin_langs');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $locale['ok'];
    $this->message = $locale['edit_success'];
  }


  function getiteminfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_admin_langs');
    $builder->select('tbl_admin_langs.*');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query->getRow(0);
  }
}
