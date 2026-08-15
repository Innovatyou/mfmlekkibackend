<?php

namespace App\Models;

use App\Models\Basemodel;

class Groups_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function fetchmygroups($email, $page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select('tbl_groups.*');
    $builder->join('tbl_group_members', 'tbl_group_members.groupid=tbl_groups.id');
    $builder->where('tbl_group_members.email', $email);
    $builder->where('tbl_group_members.status', 0);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->members = $this->getGroupMembersCount($row->id);
      $row->ismember = 0;
    }
    return $result;
  }

  public function get_my_total_groups($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->select("COUNT(*) as num");
    $builder->where('tbl_group_members.email', $email);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function fetch_items($email, $page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select('tbl_groups.*');
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->members = $this->getGroupMembersCount($row->id);
      $row->ismember = empty($this->checkMemberGroupExists($email, $row->id)) ? 1 : 0;
    }
    return $result;
  }

  public function get_total_items()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function groupsListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select('tbl_groups.*');
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->count = $this->getGroupMembersCount($res->id);
    }
    return $result;
  }

  public function getGroupMembersCount($groupid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->select("COUNT(*) as num");
    $builder->where('groupid', $groupid);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function addNewGroup($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_success'];
    return $db->insertID();
  }


  function editGroup($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_edit'];
  }


  function getGroupInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->select('tbl_groups.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function deleteGroup($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_groups');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_delete'];
  }

  function deleteGroupMembers($listid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->where('groupid', $listid);
    $builder->delete();
  }

  function removeFromGroup($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['remove_member'];
  }

  function editMemberStatus($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_member_edit'];
  }

  function addNewGroupMember($info)
  {
    if (empty($this->checkMemberGroupExists($info['email'], $info['groupid']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_group_members');
      $builder->insert($info);
    }
  }

  function getGroupMemberInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->select('tbl_group_members.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function checkMemberGroupExists($email, $groupid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->select("id");
    $builder->where("email", $email);
    $builder->where("groupid", $groupid);
    $query = $builder->get();
    return $query->getResult();
  }

  function groupsMembersListing($groupid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_members');
    $builder->select('tbl_group_members.*');
    $builder->where("groupid", $groupid);
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

  function fetchMembersNotinGroup($group)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $subQuery = $db->table('tbl_group_members')->select('email')->where('groupid', $group->id)->get();
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


  //group events
  function groupEventsListing($groupid)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->select('tbl_group_events.*');
    $builder->where('groupid', $groupid);
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->thumbnail = $this->request_base_url() . "/uploads/thumbnails/events/" . $res->thumbnail;
    }
    return $result;
  }


  function addNewEvent($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_event_add'];
    return $this->db->insertID();

    return 0;
  }


  function editEvent($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_event_edit'];
  }


  function getEventInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->select('tbl_group_events.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->thumbnail = $this->request_base_url() . "/uploads/thumbnails/events/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteEvent($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['group_event_delete'];
  }

  function fetchMonthsEvents($groupid, $month, $year)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_group_events');
    $builder->where('groupid', $groupid);
    $builder->where('month', $month);
    $builder->where('year', $year);
    $builder->orderBy('date', 'desc');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->thumbnail = $this->request_base_url() . "/uploads/thumbnails/events/" . $res->thumbnail;
    }
    return $result;
  }
}
