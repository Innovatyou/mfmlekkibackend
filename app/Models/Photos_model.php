<?php

namespace App\Models;

use App\Models\Basemodel;

class Photos_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function fetch_photos($page = 0)
  {
    $this->db = \Config\Database::connect();
    $builder = $this->db->table('tbl_photos');
    $builder->select('tbl_photos.*');
    $builder->orderBy('id', 'DESC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      $res->date = date('F j, Y', strtotime($res->date));
      if ($res->thumbnail != "") {
        $media = json_decode($res->thumbnail);
        $res->thumbnail = [];
        foreach ($media as $mdia) {
          $mdia = $this->request_base_url() . "uploads/photos/" . rawurlencode($mdia);
          array_push($res->thumbnail, $mdia);
        }
      }
    }
    return $result;
  }

  public function get_total_photos()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('tbl_photos');
    $builder->select("COUNT(id) AS num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function photosListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_photos');
    $builder->select('tbl_photos.*');
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $media = json_decode($res->thumbnail);
        $res->thumbnail = [];
        foreach ($media as $mdia) {
          $mdia = $this->request_base_url() . "uploads/photos/" . rawurlencode($mdia);
          array_push($res->thumbnail, $mdia);
        }
      }
    }
    return $result;
  }


  function addNewPhoto($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_photos');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
  }


  function getPhotoInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_photos');
    $builder->select('tbl_photos.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function editPhoto($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_photos');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['photo_details_edit'];
  }

  function deletePhoto($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_photos');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['photo_details_del'];
  }
}
