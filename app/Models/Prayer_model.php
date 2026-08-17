<?php

namespace App\Models;

use App\Models\Basemodel;

class Prayer_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetch_items($page = 0, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->select('tbl_prayers.*');
    $builder->where('status', 0);
    if ($email != "") {
      $builder->where("public = 0 OR email = '" . $email . "'");
    } else {
      $builder->where("public", 0);
    }
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

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  public function get_total_items($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->select("COUNT(*) as num");
    $builder->where('status', 0);
    if ($email != "") {
      $builder->where("public = 0 OR email = '" . $email . "'");
    } else {
      $builder->where("public", 0);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function itemsListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->select('tbl_prayers.*');
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewItem($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['prayer_added'];
  }


  function editItem($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['prayer_updated'];
  }


  function getItemInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->select('tbl_prayers.*');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query->getRow(0);
  }

  function deleteItem($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_prayers');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['prayer_del'];
  }
}
