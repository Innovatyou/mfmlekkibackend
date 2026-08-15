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

  function getLatestMembers($email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.id, tbl_members.email, tbl_members.id, tbl_members.firstname, tbl_members.lastname, tbl_members.thumbnail, tbl_members.coverphoto');
    $builder->where('email !=', $email);
    $builder->orderBy('id', 'DESC');
    $builder->limit(12);

    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      if ($res->thumbnail != "") {
        $res->thumbnail = $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      }
      if ($res->coverphoto != "") {
        $res->coverphoto = $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      }
    }
    return $result;
  }

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function adminMembersListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
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
        $res->thumbnail = $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      }
    }
    return $result;
  }

  public function get_total_members($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("COUNT(*) as num");
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

  function getMembers()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.email, tbl_members.phonenumber');
    $query = $builder->get();
    return $query->getResult();
  }

  function getMembersByListid($list)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.email, tbl_members.phonenumber');
    $subQuery = $db->table('tbl_list_members')->select('email')->where('listid', $list)->get();
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

  function checkMembersExists($email, $id = 0)
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("id");
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
    if (empty($this->checkMembersExists($info['email'], 0))) {
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


  function editMember($info, $id)
  {
    if (empty($this->checkMembersExists($info['email'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_members');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['member_detail_deleted'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['member_email_exist'] . $info['email'];
    }
  }


  function getMemberInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0 && $row->thumbnail != "") {
      if ($row->thumbnail != "") {
        $row->thumbnail = $this->request_base_url() . "/uploads/members/" . $row->thumbnail;
      }
    }
    return $row;
  }

  function deleteMember($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['member_del_success'];
  }

  /**
   * Public website "Join Us" form submission.
   * New signups land as pending until an admin approves them from the dashboard.
   */
  function publicSignup($info)
  {
    $db = \Config\Database::connect("default");
    if (empty($this->checkMembersExists($info['email'], 0))) {
      $info['signup_status'] = 'pending';
      $info['signup_source'] = 'public_website';
      $builder = $db->table('tbl_members');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = "Thank you! Your membership request has been received and is awaiting review.";
      return $db->insertID();
    } else {
      $this->status = $this->applocal['error'];
      $this->message = "This email is already registered with us: " . $info['email'];
      return 0;
    }
  }

  function getPendingSignupsListing($searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('signup_status', 'pending');
    if ($searchValue != "") {
      $builder->groupStart();
      $builder->like('email', $searchValue);
      $builder->orlike('firstname', $searchValue);
      $builder->orlike('lastname', $searchValue);
      $builder->orlike('phonenumber', $searchValue);
      $builder->groupEnd();
    }
    $builder->orderby('date_inserted', 'DESC');
    $builder->limit($length, $start);
    return $builder->get()->getResult();
  }

  function getPendingSignupsTotal($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select("COUNT(*) as num");
    $builder->where('signup_status', 'pending');
    if ($searchValue != "") {
      $builder->groupStart();
      $builder->like('email', $searchValue);
      $builder->orlike('firstname', $searchValue);
      $builder->orlike('lastname', $searchValue);
      $builder->orlike('phonenumber', $searchValue);
      $builder->groupEnd();
    }
    $result = $builder->get()->getRow(0);
    return isset($result) ? $result->num : 0;
  }

  function approveSignup($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('id', $id);
    $builder->update(['signup_status' => 'approved']);
    $this->status = $this->applocal['ok'];
    $this->message = "Signup request approved. The member has been added.";
  }

  function rejectSignup($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('id', $id);
    $builder->update(['signup_status' => 'rejected']);
    $this->status = $this->applocal['ok'];
    $this->message = "Signup request rejected.";
  }
}
