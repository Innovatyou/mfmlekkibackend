<?php

namespace App\Models;

use App\Models\Basemodel;

class Livestream_model extends Basemodel
{
  public $status;
  public $message;

  // Force casting to ensure streamUrl is always returned as string
  protected $casts = [
    'link' => 'string',
  ];

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetch_livestreams_app($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
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
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $apitoken);
    }
    return $result;
  }

  public function fetch_livestreams($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('title', 'ASC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }


  public function get_total_livestreams($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->where('status', 0);
    $query = $builder->select("COUNT(*) as num")->where('apitoken', $apitoken)->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function livestreamsListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    return $query->getResult();
  }

  function checkLivestreamExists($title, $id = 0, $apitoken = "")
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
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


  function addNewLivestream($info)
  {
    if (empty($this->checkLivestreamExists($info['title'], 0, $info['apitoken']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['ok'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['livestream_exist'];
    }
  }


  function editLivestream($info, $id, $apitoken)
  {
    if (empty($this->checkLivestreamExists($info['title'], $id, $apitoken))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->where('apitoken', $apitoken);
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['new_livestream_edit'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['livestream_exist'];
    }
  }


  function getLivestreamInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $apitoken);
    }
    return $row;
  }

  function deleteLivestream($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->where('apitoken', $apitoken);
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['new_livestream_delete'];
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
