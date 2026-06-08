<?php

namespace App\Models;

use App\Models\Basemodel;

class Inbox_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function get_last_seen_notification_count($email = "", $last_seen_inbox = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select("COUNT(*) as num");
    $builder->where('timestamp >', intval($last_seen_inbox));

    if ($email != "") {
      $builder->where('tbl_notifications.user', $email);
      $builder->orwhere('tbl_notifications.user', "");
    } else {
      $builder->where('tbl_notifications.type', "inbox");
    }
    if ($email != "") {
      $builder->where("(apitoken = '" . $apitoken . "') AND (type = 'inbox' OR user = '" . $email . "')");
    } else {
      $builder->where('apitoken', $apitoken);
      $builder->where('tbl_notifications.type', "inbox");
    }

    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function getUnseenSocialNotificationCount($email = "", $last_seen_inbox = 0, $apitoken = "")
  {
    if ($email == "") return 0;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select("COUNT(*) as num");

    //$builder->where('apitoken', $apitoken);
    $builder->where('tbl_notifications.user', $email);
    $builder->where('timestamp >', intval($last_seen_inbox));

    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }
   

  public function fetch_app_inbox($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select('tbl_notifications.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function fetchInbox($page = 0, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select('tbl_notifications.*');
    //$builder->where('apitoken', $apitoken);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }

    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }


  public function get_total_inbox($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $query = $builder->select("COUNT(*) as num")->where('apitoken', $apitoken)->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function inboxListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select('tbl_notifications.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('type', 'inbox');
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function addNewInbox($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    return $db->insertID();
  }


  function editInbox($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }


  function getInboxInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select('tbl_notifications.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function deleteInbox($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['delete_inbox'];
  }
}
