<?php

namespace App\Models;

use App\Models\Basemodel;

class Lists_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }


  function listsListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->select('tbl_lists.*');
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->count = $this->getListMembersCount($res->id);
    }
    return $result;
  }

  function listsListingbybranch()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->select('tbl_lists.*');
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->count = $this->getListMembersCount($res->id);
    }
    return $result;
  }

  public function getListMembersCount($listid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_list_members');
    $builder->select("COUNT(*) as num");
    $builder->where('listid', $listid);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function addNewList($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['list_success'];
    return $db->insertID();
  }


  function editList($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['edit_list'];
  }


  function getListInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->select('tbl_lists.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function deleteList($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_lists');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['list_delete_success'];
  }

  function deleteListMembers($listid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_list_members');
    $builder->where('listid', $listid);
    $builder->delete();
  }

  function removeFromList($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_list_members');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['remove_list_member'];
  }

  function addNewListMember($info)
  {
    if (empty($this->checkMemberListExists($info['email'], $info['listid']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_list_members');
      $builder->insert($info);
    }
  }

  function checkMemberListExists($email, $listid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_list_members');
    $builder->select("id");
    $builder->where("email", $email);
    $builder->where("listid", $listid);
    $query = $builder->get();
    return $query->getResult();
  }

  function listsMembersListing($listid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_list_members');
    $builder->select('tbl_list_members.*');
    $builder->where("listid", $listid);
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->name = $this->getMemberName($res->email);
    }
    return $result;
  }

  function getMemberName($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.firstname, tbl_members.lastname');
    $builder->where('email', $email);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return $row->firstname . " " . $row->lastname;
    } else {
      return "---";
    }
  }

  function fetchMembersNotinList($list)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $subQuery = $db->table('tbl_list_members')->select('email')->where('listid', $list->id)->get();
    $items = $subQuery->getResult();
    $_itms = [];
    foreach ($items as $ress) {
      array_push($_itms, $ress->email);
    }
    if (count($items) > 0) {
      $builder->whereNotIn('email', $_itms);
    }

    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->name = $this->getMemberName($res->email);
    }
    return $result;
  }

  function getBranchMembers()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->name = $this->getMemberName($res->email);
    }
    return $result;
  }
}
