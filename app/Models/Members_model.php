<?php

namespace App\Models;

use App\Models\Basemodel;

class Members_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getLatestMembers($apitoken, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.id, tbl_members.email, tbl_members.id, tbl_members.firstname, tbl_members.lastname, tbl_members.thumbnail, tbl_members.coverphoto');
    $builder->where('apitoken', $apitoken);
    $builder->where('email !=', $email);
    $builder->orderBy('id', 'DESC');
    $builder->limit(12);

    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $res->thumbnail = base_url() . "/uploads/members/" . $apitoken . "/" . $res->thumbnail;
      }
      if ($res->coverphoto != "") {
        $res->coverphoto = base_url() . "/uploads/members/" . $apitoken . "/" . $res->coverphoto;
      }
    }
    return $result;
  }

  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function adminMembersListing($columnName, $columnSortOrder, $searchValue, $start, $length, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('apitoken', $apitoken);
    if ($searchValue != "") {
      $builder->like('email', $searchValue);
      $builder->orlike('firstname', $searchValue);
      $builder->orlike('lastname', $searchValue);
    }
    if ($columnName != "") {
      $builder->orderby($columnName, $columnSortOrder);
    }
    $builder->limit($length, $start);

    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $res->thumbnail = base_url() . "/uploads/members/" . $apitoken . "/" . $res->thumbnail;
      }
    }
    return $result;
  }

  public function get_total_members($searchValue = "", $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    if ($searchValue != "") {
      $builder->like('email', $searchValue);
      $builder->orlike('firstname', $searchValue);
      $builder->orlike('lastname', $searchValue);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function getMembers($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.email, tbl_members.phonenumber');
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    return $query->getResult();
  }

  function getMembersByListid($list, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.email, tbl_members.phonenumber');
    $builder->where('apitoken', $apitoken);
    $subQuery = $db->table('tbl_list_members')->select('email')->where('apitoken', $apitoken)->where('listid', $list)->get();
    $items = $subQuery->getResult();
    $_itms = [];
    foreach ($items as $ress) {
      array_push($_itms, $ress->email);
    }
    //var_dump($_itms); die;
    if (count($items) > 0) {
      $builder->whereIn('email', $_itms);
    }

    $query = $builder->get();
    $result =  $query->getResult();
    return $result;
  }

  function checkMembersExists($email, $id = 0, $apitoken = "")
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("id");
    $builder->where('apitoken', $apitoken);
    $builder->where("email", $email);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewMember($info)
  {
    $db = \Config\Database::connect("default");
    if (empty($this->checkMembersExists($info['email'], 0, $info['apitoken']))) {
      $builder = $db->table('tbl_members');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['member_add_success'];
      return $db->insertID();
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['member_email_exist'] . $info['email'];
      return 0;
    }
  }


  function editMember($info, $id, $apitoken)
  {
    if (empty($this->checkMembersExists($info['email'], $id, $apitoken))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_members');
      $builder->where('id', $id);
      $builder->where('apitoken', $apitoken);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['member_detail_deleted'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['member_email_exist'] . $info['email'];
    }
  }


  function getMemberInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      if ($row->thumbnail != "") {
        $row->thumbnail = base_url() . "/uploads/members/" . $apitoken . "/" . $row->thumbnail;
      }
    }
    return $row;
  }

  function deleteMember($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['member_del_success'];
  }
}
