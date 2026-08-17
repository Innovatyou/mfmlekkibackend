<?php

namespace App\Models;

use App\Models\Basemodel;

class Hymns_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function hymnsListing($page, $searchValue)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->select('tbl_hymns.*');
    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('content', $searchValue);
    }
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      if ($row->thumbnail != "") {
        $row->thumbnail = $this->request_base_url() . "/uploads/thumbnails/" . $row->thumbnail;
      }
    }
    return $result;
  }

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function adminHymnsListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->select('tbl_hymns.*');
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

  public function get_total_hymns($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
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

  function checkHymnExists($title, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->select("id");
    $builder->where("title", $title);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewHymn($info)
  {
    $db = \Config\Database::connect("default");
    if (empty($this->checkHymnExists($info['title']))) {
      $builder = $db->table('tbl_hymns');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['hymn_add'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['hymn_added'] . $info['title'];
    }
    return $db->insertID();

    return 0;
  }


  function editHymn($info, $id)
  {
    if (empty($this->checkHymnExists($info['title'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_hymns');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['hymn_edit'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['hymn_title_unavailable'];
    }
  }


  function getHymnInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->select('tbl_hymns.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      $row->thumbnail = $this->request_base_url() . "/uploads/thumbnails/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteHymn($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_hymns');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['hymn_delete_success'];
  }
}
