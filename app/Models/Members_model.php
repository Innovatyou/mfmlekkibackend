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
      if (!$builder->insert($info)) {
        $this->status = $this->applocal['error'];
        $this->message = 'Could not save the member. Please try again.';
        return 0;
      }
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

  // ─── Dashboard: overview stats ─────────────────────────────────────

  public function getDashboardStats(): array
  {
    $db = \Config\Database::connect('default');

    $total   = (int) $db->table('tbl_members')->countAllResults();
    $pending = (int) $db->table('tbl_members')->where('signup_status', 'pending')->countAllResults();

    $monthStart = date('Y-m-01 00:00:00');
    $newThisMonth = (int) $db->table('tbl_members')
      ->where('date_inserted >=', $monthStart)
      ->countAllResults();

    $lastMonthStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
    $newLastMonth = (int) $db->table('tbl_members')
      ->where('date_inserted >=', $lastMonthStart)
      ->where('date_inserted <', $monthStart)
      ->countAllResults();

    $avgAge = $db->table('tbl_members')->where('age >', 0)->selectAvg('age')->get()->getRow()->age ?? 0;

    return [
      'total'          => $total,
      'pending'        => $pending,
      'new_this_month' => $newThisMonth,
      'new_last_month' => $newLastMonth,
      'avg_age'        => round((float) $avgAge),
    ];
  }

  // ─── Dashboard: gender split ────────────────────────────────────────

  public function getGenderBreakdown(): array
  {
    $db  = \Config\Database::connect('default');
    $rows = $db->table('tbl_members')
      ->select("CASE
                  WHEN LOWER(gender) = 'male' THEN 'Male'
                  WHEN LOWER(gender) = 'female' THEN 'Female'
                  ELSE 'Unspecified'
                END AS gender_label, COUNT(*) AS total", false)
      ->groupBy('gender_label')
      ->get()->getResult();

    $out = ['Male' => 0, 'Female' => 0, 'Unspecified' => 0];
    foreach ($rows as $r) {
      $out[$r->gender_label] = (int) $r->total;
    }
    return $out;
  }

  // ─── Dashboard: age distribution ────────────────────────────────────

  public function getAgeBreakdown(): array
  {
    $db  = \Config\Database::connect('default');
    $rows = $db->table('tbl_members')
      ->select("CASE
                  WHEN age IS NULL OR age <= 0 THEN 'Unknown'
                  WHEN age < 18 THEN 'Under 18'
                  WHEN age BETWEEN 18 AND 25 THEN '18–25'
                  WHEN age BETWEEN 26 AND 35 THEN '26–35'
                  WHEN age BETWEEN 36 AND 45 THEN '36–45'
                  WHEN age BETWEEN 46 AND 60 THEN '46–60'
                  ELSE '60+'
                END AS bracket, COUNT(*) AS total", false)
      ->groupBy('bracket')
      ->get()->getResult();

    $order = ['Under 18', '18–25', '26–35', '36–45', '46–60', '60+', 'Unknown'];
    $out = array_fill_keys($order, 0);
    foreach ($rows as $r) {
      $out[$r->bracket] = (int) $r->total;
    }
    return $out;
  }

  // ─── Dashboard: signup growth, last 12 months ──────────────────────

  public function getGrowthTrend(): array
  {
    $db = \Config\Database::connect('default');
    $rows = $db->table('tbl_members')
      ->select("DATE_FORMAT(date_inserted, '%Y-%m') AS ym, COUNT(*) AS total", false)
      ->where('date_inserted >=', date('Y-m-01 00:00:00', strtotime('-11 months')))
      ->groupBy('ym')
      ->orderBy('ym', 'ASC')
      ->get()->getResult();

    $byMonth = [];
    foreach ($rows as $r) {
      $byMonth[$r->ym] = (int) $r->total;
    }

    $labels = [];
    $data   = [];
    for ($i = 11; $i >= 0; $i--) {
      $ym = date('Y-m', strtotime("-$i months"));
      $labels[] = date('M Y', strtotime("-$i months"));
      $data[]   = $byMonth[$ym] ?? 0;
    }

    return ['labels' => $labels, 'data' => $data];
  }

  // ─── Dashboard: signup source (admin vs mobile) ────────────────────

  public function getSignupSourceBreakdown(): array
  {
    $db  = \Config\Database::connect('default');
    $rows = $db->table('tbl_members')
      ->select('signup_source, COUNT(*) AS total')
      ->groupBy('signup_source')
      ->get()->getResult();

    $out = [];
    foreach ($rows as $r) {
      $label = $r->signup_source !== '' ? ucfirst($r->signup_source) : 'Admin';
      $out[$label] = ($out[$label] ?? 0) + (int) $r->total;
    }
    return $out;
  }
}
