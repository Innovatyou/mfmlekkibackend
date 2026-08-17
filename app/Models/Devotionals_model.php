<?php

namespace App\Models;


use App\Models\Basemodel;

class Devotionals_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function fetchMonthsDevotionals($month, $year)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->where('month', $month);
    $builder->where('year', $year);
    $builder->orderBy('date', 'desc');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $res->thumbnail = $this->request_base_url() . "/uploads/thumbnails/" . $res->thumbnail;
      }
    }
    return $result;
  }

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function adminDevotionalsListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select('tbl_devotionals.*');
    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('content', $searchValue);
    }
    if ($columnName != "") {
      $builder->orderby($columnName, $columnSortOrder);
    }
    $builder->limit($length, $start);

    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  public function get_total_devotionals($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    if ($searchValue == "") {
      $query = $builder->select("COUNT(*) as num")->get();
    } else {
      $builder->select("COUNT(*) as num");
      $builder->like('title', $searchValue);
      $builder->orlike('content', $searchValue);
      $query = $builder->get();
    }
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function checkDevotionalExists($date, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select("id");
    $builder->where("date", $date);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewDevotional($info)
  {
    $db = \Config\Database::connect("default");
    if (empty($this->checkDevotionalExists($info['date']))) {

      $builder = $db->table('tbl_devotionals');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['devotional_success'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['devo_added_already'] . $info['date'];
    }
    return $db->insertID();

    return 0;
  }


  function editDevotional($info, $id)
  {
    if (empty($this->checkDevotionalExists($info['date'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_devotionals');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['devotional_edit_success'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['devotional_date_exists'];
    }
  }


  function getDevotionalInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select('tbl_devotionals.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      $row->thumbnail = $this->request_base_url() . "/uploads/thumbnails/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteDevotional($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['devotional_delete_success'];
  }
}
