<?php

namespace App\Models;

use App\Models\Basemodel;

class Testimony_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetch_items($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->select('tbl_testimonies.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('status', 0);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->date = date('F j, Y', strtotime($row->date));
    }
    return $result;
  }

  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  public function get_total_items($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $query = $builder->select("COUNT(*) as num")->where('apitoken', $apitoken)->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function itemsListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->select('tbl_testimonies.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('title', 'ASC');
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewItem($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['testimony_added'];
  }


  function editItem($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['testimony_updated'];
  }


  function getItemInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->select('tbl_testimonies.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    return $query->getRow(0);
  }

  function deleteItem($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_testimonies');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['testimony_del'];
  }
}
