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

  public function fetch_radio($page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
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
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
    }
    return $result;
  }


  public function get_total_radio()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->where('status', 0);
    $query = $builder->select("COUNT(*) as num")->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function radioListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
    $builder->orderBy('title', 'ASC');
    $query = $builder->get();
    return $query->getResult();
  }

  function checkRadioExists($title, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select("title");
    $builder->where("title", $title);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewRadio($info)
  {
    if (empty($this->checkRadioExists($info['title']))) {
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


  function editRadio($info, $id)
  {
    if (empty($this->checkRadioExists($info['title'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_radio');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['radio_edit'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['radio_exist'];
    }
  }


  function getRadioInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->select('tbl_radio.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
    }
    return $row;
  }

  function deleteRadio($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_radio');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['radio_del'];
  }

  private function get_thumbnail_source($source)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return $this->request_base_url() . "/uploads/thumbnails/" . $source;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
