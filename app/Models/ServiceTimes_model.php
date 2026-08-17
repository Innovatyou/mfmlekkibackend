<?php

namespace App\Models;

use App\Models\Basemodel;

class ServiceTimes_model extends Basemodel
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
    $builder = $db->table('tbl_service_times');
    $builder->select('tbl_service_times.*');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    return $builder->get()->getResult();
  }

  function fetchActive()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_service_times');
    $builder->select('tbl_service_times.*');
    $builder->where('status', 'active');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    return $builder->get()->getResult();
  }

  function getInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_service_times');
    $builder->select('tbl_service_times.*');
    $builder->where('id', $id);
    return $builder->get()->getRow(0);
  }

  function addNew($info)
  {
    $db = \Config\Database::connect("default");
    $info['created_at'] = date('Y-m-d H:i:s');
    $builder = $db->table('tbl_service_times');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Service time added successfully.";
  }

  function edit($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_service_times');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Service time updated successfully.";
  }

  function deleteItem($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_service_times');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = "Service time deleted successfully.";
  }
}
