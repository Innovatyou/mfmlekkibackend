<?php

namespace App\Models;

use App\Models\Basemodel;

class ContactMessage_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function addMessage($info)
  {
    $db = \Config\Database::connect("default");
    $info['status'] = 'unread';
    $info['created_at'] = date('Y-m-d H:i:s');
    $builder = $db->table('tbl_contact_messages');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Thank you for reaching out! We'll get back to you soon.";
    return $db->insertID();
  }

  function getListing($searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->select('tbl_contact_messages.*');
    if ($searchValue != "") {
      $builder->groupStart();
      $builder->like('name', $searchValue);
      $builder->orlike('email', $searchValue);
      $builder->orlike('subject', $searchValue);
      $builder->orlike('message', $searchValue);
      $builder->groupEnd();
    }
    $builder->orderby('created_at', 'DESC');
    $builder->limit($length, $start);
    return $builder->get()->getResult();
  }

  function getTotal($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->select("COUNT(*) as num");
    if ($searchValue != "") {
      $builder->groupStart();
      $builder->like('name', $searchValue);
      $builder->orlike('email', $searchValue);
      $builder->orlike('subject', $searchValue);
      $builder->orlike('message', $searchValue);
      $builder->groupEnd();
    }
    $result = $builder->get()->getRow(0);
    return isset($result) ? $result->num : 0;
  }

  function getUnreadTotal()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->select("COUNT(*) as num");
    $builder->where('status', 'unread');
    $result = $builder->get()->getRow(0);
    return isset($result) ? $result->num : 0;
  }

  function getInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->select('tbl_contact_messages.*');
    $builder->where('id', $id);
    return $builder->get()->getRow(0);
  }

  function markAsRead($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->where('id', $id);
    $builder->where('status', 'unread');
    $builder->update(['status' => 'read']);
  }

  function saveReply($id, $reply)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->where('id', $id);
    $builder->update([
      'admin_reply' => $reply,
      'status'      => 'replied',
      'replied_at'  => date('Y-m-d H:i:s'),
    ]);
    $this->status = $this->applocal['ok'];
    $this->message = "Reply sent successfully.";
  }

  function deleteMessage($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_contact_messages');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = "Message deleted successfully.";
  }
}
