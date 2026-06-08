<?php

namespace App\Models;

use App\Models\Basemodel;
use CodeIgniter\Model;

class Books_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function biblesListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_bible_versions');
    $builder->select('tbl_bible_versions.*');
    $builder->orderby('name', 'ASC');
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      $res->source = base_url() . "/uploads/" . $res->source;
    }
    return $result;
  }

  function getLatestBooks($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->select('tbl_books.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderby('id', 'desc');
    $builder->limit(6);
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      $row->book = base_url() . "/uploads/books/" . $apitoken . "/" . $row->book;
    }
    return $result;
  }

  public function fetch_books($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->select('tbl_books.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      $row->book = base_url() . "/uploads/books/" . $apitoken . "/" . $row->book;
    }
    return $result;
  }

  public function get_total_books($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
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
    $builder = $db->table('tbl_books');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function booksListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->select('tbl_books.*');
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      $row->book = base_url() . "/uploads/books/" . $apitoken . "/" . $row->book;
    }
    return $result;
  }


  function addNewBook($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['book_added'];
    
    

  }


  function editBook($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['book_edit'];
    
    

  }


  function getBookInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->select('tbl_books.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      $row->thumb = $row->thumbnail;
      $row->pdf = $row->book;
      $row->thumbnail = base_url() . "/uploads/thumbnails/" . $apitoken . "/" . $row->thumbnail;
      $row->book = base_url() . "/uploads/books/" . $apitoken . "/" . $row->book;
    }
    return $row;
  }

  function deleteBook($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_books');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['book_delete'];
    
    

  }
}
