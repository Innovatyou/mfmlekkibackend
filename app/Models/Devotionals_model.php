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

  function fetchMonthsDevotionals($month, $year, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->where('apitoken', $apitoken);
    $builder->where('month', $month);
    $builder->where('year', $year);
    $builder->orderBy('date', 'desc');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $res->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $res->thumbnail;
      }
    }
    return $result;
  }

  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function adminDevotionalsListing($columnName, $columnSortOrder, $searchValue, $start, $length, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select('tbl_devotionals.*');
    $builder->where('apitoken', $apitoken);
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

  public function get_total_devotionals($searchValue = "", $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->where('apitoken', $apitoken);
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

  function checkDevotionalExists($date, $id = 0, $apitoken = "")
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select("id");
    $builder->where('apitoken', $apitoken);
    $builder->where("date", $date);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    //var_dump($query->result()); die;
    return $query->getResult();
  }


  function addNewDevotional($info)
  {
    $db = \Config\Database::connect("default");
    if (empty($this->checkDevotionalExists($info['date'], 0, $info['apitoken']))) {

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


  function editDevotional($info, $id, $apitoken)
  {
    if (empty($this->checkDevotionalExists($info['date'], $id, $apitoken))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_devotionals');
      $builder->where('apitoken', $apitoken);
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['devotional_edit_success'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['devotional_date_exists'];
    }
    
    

  }


  function getDevotionalInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->select('tbl_devotionals.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteDevotional($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_devotionals');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['devotional_delete_success'];
    
    

  }
}
