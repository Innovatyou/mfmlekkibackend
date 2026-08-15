<?php

namespace App\Models;

use App\Models\Basemodel;

class Leadership_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function fetchAll()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_church_leadership');
    $builder->select('tbl_church_leadership.*');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    $result = $builder->get()->getResult();
    foreach ($result as $res) {
      $res->photo = $res->photo != "" ? $this->request_base_url() . "uploads/leadership/" . $res->photo : "";
    }
    return $result;
  }

  function fetchActive()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_church_leadership');
    $builder->select('tbl_church_leadership.*');
    $builder->where('status', 'active');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    $result = $builder->get()->getResult();
    foreach ($result as $res) {
      $res->photo = $res->photo != "" ? $this->request_base_url() . "uploads/leadership/" . $res->photo : "";
    }
    return $result;
  }

  function getInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_church_leadership');
    $builder->select('tbl_church_leadership.*');
    $builder->where('id', $id);
    $row = $builder->get()->getRow(0);
    if ($row && $row->photo != "") {
      $row->photo = $this->request_base_url() . "uploads/leadership/" . $row->photo;
    }
    return $row;
  }

  function addNew($info)
  {
    $db = \Config\Database::connect("default");
    $info['created_at'] = date('Y-m-d H:i:s');
    $builder = $db->table('tbl_church_leadership');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Leader added successfully.";
  }

  function edit($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_church_leadership');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Leader updated successfully.";
  }

  function deleteItem($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_church_leadership');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = "Leader deleted successfully.";
  }
}
