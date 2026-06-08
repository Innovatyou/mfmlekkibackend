<?php

namespace App\Models;

use App\Models\Basemodel;

class Radio_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetch_radio($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('status', 0);
    $builder->orderBy('title', 'ASC');

    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $apitoken);
    }
    return $result;
  }


  public function get_total_radio($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->where('status', 0);
    $query = $builder->select("COUNT(*) as num")->where('apitoken', $apitoken)->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function radioListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('title', 'ASC');
    $query = $builder->get();
    return $query->getResult();
  }

  function checkRadioExists($title, $id = 0, $apitoken = "")
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select("title");
    $builder->where('apitoken', $apitoken);
    $builder->where("title", $title);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    //var_dump($query->result()); die;
    return $query->getResult();
  }


  function addNewRadio($info)
  {
    if (empty($this->checkRadioExists($info['title'], 0, $info['apitoken']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_radio');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['radio_add'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['radio_exist'];
    }
    
    

  }


  function editRadio($info, $id, $apitoken)
  {
    if (empty($this->checkRadioExists($info['title'], $id, $apitoken))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_radio');
      $builder->where('id', $id);
      $builder->where('apitoken', $apitoken);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['radio_edit'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['radio_exist'];
    }
    
    

  }


  function getRadioInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $apitoken);
    }
    return $row;
  }

  function deleteRadio($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['radio_del'];
    
    

  }

  private function get_thumbnail_source($source, $apitoken)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $source;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
