<?php

namespace App\Models;

use App\Models\Basemodel;

class Messaging_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetch_app_messages($page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->select('tbl_messaging.*');
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }


  public function get_total_messages()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $query = $builder->select("COUNT(*) as num")->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function messageListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->select('tbl_messaging.*');
    $builder->orderBy('date_created', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      if ($res->listid == 0) {
        $res->listname = "All Members";
      } else {
        $res->listname = $this->getListName($res->listid);
      }
    }
    return $result;
  }

  function getListName($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->select('tbl_lists.title');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return $row->title;
    } else {
      return "---";
    }
  }

  function addNewMessage($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
  }


  function editMessage($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }


  function getMessageInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->select('tbl_messaging.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function deleteMessage($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_messaging');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['msg_delete'];
  }
}
