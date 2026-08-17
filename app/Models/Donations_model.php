<?php

namespace App\Models;

use App\Models\Basemodel;

class Donations_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->select("COUNT(*) as num");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function getThisWeekDonationsAmount($date1, $date2)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->selectSum("amount");
    $builder->where("(DATE(date) BETWEEN '" . $date1 . "' AND '" . $date2 . "')");
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) {
      if ($result->amount == "") return 0;
      return $result->amount;
    }
    return 0;
  }

  function getDonationsAmount($month, $year)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->selectSum("amount");
    if ($month != 0) {
      $builder->where('month', $month);
    }
    if ($year != 0) {
      $builder->where('year', $year);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) {
      if ($result->amount == "") return 0;
      return $result->amount;
    }
    return 0;
  }

  function getRecentDonations()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->select('tbl_donations.*');
    $builder->orderby('date', 'DESC');
    $builder->limit(10);
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function donationsListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {

    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->select('tbl_donations.*');
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

  public function get_total_donations($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
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


  public function recordDonation($ref)
  {
    if ($this->verifyPaymentRefExists($ref['email'], $ref['reference']) == FALSE) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_donations');
      $builder->insert($ref);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['donation_success'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = $this->applocal['donation_not_recorded'];
    }
  }


  function verifyPaymentRefExists($email, $ref)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
    $builder->select('tbl_donations.id');
    $builder->where('email', $email);
    $builder->where('reference', $ref);
    $query = $builder->get();
    if (count((array)$query->getResult()) > 0) {
      return TRUE;
    }
    return FALSE;
  }
}
