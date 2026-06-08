<?php

namespace App\Models;

use App\Models\Basemodel;
use CodeIgniter\Model;

class Articles_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getLatestArticles($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select('tbl_articles.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderby('date', 'desc');
    $builder->limit(5);
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      if ($row->thumbnail != "") {
        $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      }
      $row->date = date('F j, Y', strtotime($row->date));
    }
    return $result;
  }

  public function fetch_articles($apitoken, $page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select('tbl_articles.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderby('date', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      if ($row->thumbnail != "") {
        $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      }
      $row->date = date('F j, Y', strtotime($row->date));
    }
    return $result;
  }

  public function get_total_articles_app($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }



  function adminarticlesListing($columnName, $columnSortOrder, $searchValue, $start, $length, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select('tbl_articles.*');
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
    foreach ($result as $res) {
      $res->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $res->thumbnail;
    }
    return $result;
  }

  public function get_total_articles($searchValue = "", $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('content', $searchValue);
    }

    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function addNewArticle($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['article_added'];
    return $db->insertID();
    
    
    
    return 0;
  }


  function editArticle($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['article_edited'];
    
    
    

  }


  function getArticleInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->select('tbl_articles.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteArticle($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_articles');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['article_deleted'];
    
    
    

  }
}
