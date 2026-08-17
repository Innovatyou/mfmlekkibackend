<?php

namespace App\Models;

use App\Models\Basemodel;

class Manage_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getTotalChurches($type)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('COUNT(*) as num');
    $builder->where('role', 2);
    if ($type == 1) {
      $builder->where("(DATE(expiry_date) > '" . date('Y-m-d H:i:s') . "')");
    }
    if ($type == 2) {
      $builder->where("(DATE(expiry_date) < '" . date('Y-m-d H:i:s') . "')");
    }

    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function getRecentTransactions()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_transactions');
    $builder->select('tbl_transactions.*');
    $builder->orderby('date', 'DESC');
    $builder->limit(20);
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function transactionsListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {

    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_transactions');
    $builder->select('tbl_transactions.*');
    if ($searchValue != "") {
      $builder->like('name', $searchValue);
      $builder->orlike('email', $searchValue);
      $builder->orlike('reference', $searchValue);
      $builder->orlike('amount', $searchValue);
    }
    if ($columnName != "") {
      $builder->orderby($columnName, $columnSortOrder);
    }
    $builder->limit($length, $start);
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  public function get_total_transactions($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_transactions');
    $builder->select("COUNT(*) as num");
    if ($searchValue != "") {
      $builder->like('name', $searchValue);
      $builder->orlike('email', $searchValue);
      $builder->orlike('reference', $searchValue);
      $builder->orlike('amount', $searchValue);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function getAllChurches()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $builder->where('role', 2);
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->substatus = 0;
      $startDate = strtotime(date('Y-m-d', strtotime($res->expiry_date)));
      $currentDate = strtotime(date('Y-m-d'));
      if ($startDate < $currentDate) {
        $res->substatus = 1;
      }
    }
    return $result;
  }

  function checkEmailExists($email, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select("email");
    $builder->where("email", $email);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    return $query->getResult();
  }


  function addNewAdmin($info)
  {
    if (empty($this->checkEmailExists($info['email']))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_churches');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['new_admin_added'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['email_exist'];
    }
  }


  function editchurchdata($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['church_edited'];
  }


  function getChurchInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      if ($row->logo != "") {
        $row->logo = $this->request_base_url() . "/uploads/churches/" . $row->logo;
      }
    }
    return $row;
  }

  function getManagerSettings()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->select('settings.*');
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  function editmanagerdata($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_manager_settings');
    $builder->where('id', 100);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['data_edit_success'];
  }

  public function recordTransaction($ref)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_transactions');
    $builder->insert($ref);
  }

  //
  function getDeleteScheduledChurches()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $builder->where('isdelete', 0);
    $query = $builder->get();
    $result =  $query->getResult();
    return $result;
  }

  function deleteChurchData($tbl)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table($tbl);
    $builder->delete();
  }
}
