<?php

namespace App\Models;

use App\Models\Basemodel;

class Settings_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getFcmServerKey()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->select('settings.fcm_server_key');
    $query = $builder->get();
    return $query->getRow(0)->fcm_server_key;
  }

  function addNewChurchSettings($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->insert($info);
  }

  function getSettings()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->select('settings.*');
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row && $row->features == "") {
      $row->features = "bible,audiomessages, videomessages, donations, livestreams, events, articles, hymns, radio, photos, groups, prayer, testimony, devotionals, notes, books, gosocial";
    }
    if ($row && $row->donationslogo != "") {
      $row->donationslogo = $this->request_base_url() . "/uploads/churches/" . $row->donationslogo;
    }
    if ($row && empty($row->brand_color)) {
      $row->brand_color = '#6366f1';
    }
    return $row;
  }

  function getAppSettings()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->select('settings.features,
      settings.app_login,
      settings.allow_downloads,
      settings.join_groups,
      settings.post_prayer,
      settings.post_testimony,
      settings.facebook,
      settings.twitter,
      settings.instagram,
      settings.youtube,
      settings.website,
      settings.donations_link');
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row === null) {
      return null;
    }
    if ($row->features == "" || $row->features === null) {
      $row->features = "bible,audiomessages,videomessages,donations,livestreams,events,articles,hymns,radio,photos,groups,prayer,testimony,devotionals,notes,books,gosocial";
    }
    return $row;
  }


  function updateSettings($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->update($info);
    
    

  }

  public function getEmailConfig()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('settings');
    $builder->select('settings.mail_username,
     settings.mail_password,
     settings.mail_smtp_host,
     settings.mail_protocol,
     settings.mail_port');
    $query = $builder->get();
    $row = $query->getRow(0);
    return $row;
  }

  public function getSMSConfig($set2, $smsgateway)
  {
    $config = new \stdClass;
    if ($smsgateway == "twilio") {
      $config->twilio_account_sid = $set2->twilio_account_sid;
      $config->twilio_auth_token = $set2->twilio_auth_token;
      $config->twilio_phonenumber = $set2->twilio_phonenumber;
    } else {
      $config->termi_apikey = $set2->termi_apikey;
      $config->termi_sender_id = $set2->termi_sender_id;
    }
    return $config;
  }

  //
  function getRecentTransactions()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_transactions');
    $builder->select('tbl_transactions.*');
    $builder->orderby('date', 'DESC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  function getChurchProfile()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      if ($row->logo != "") {
        $row->logo = $this->request_base_url() . "/uploads/churches/" . $row->logo;
      }
      $row->substartdate = $this->calculatesubstartdate($row);
      $row->subexpirydate = date('Y-m-d H:i:s', strtotime($row->substartdate . ' +1 month'));
    }
    //var_dump($row); die;
    return $row;
  }

  function getChurchDefaultLanguage()
  {
    return 'en';
  }

  function getChurchStatus()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.status');
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return $row->status;
    }
    return 1;
  }

  function editchurchprofile($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->update($info);
  }

  function calculatesubstartdate($row)
  {
    $expiry = strtotime($row->expiry_date);
    $today = time();
    //if church is active or their subscription expiry is in future
    //return a date after the subscription expiry
    if ($row->status == 0 || ($expiry > $today)) {
      return $row->expiry_date; //date('Y-m-d H:i:s', strtotime($row->expiry_date .' +1 day'));
    } else {
      return date('Y-m-d H:i:s');
    }
  }

  function getChurchSubscriptionMessage($grace_period)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.*');
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $date1 = date_create(date('Y-m-d H:i:s'));
      $date2 = date_create($row->expiry_date);
      $diff = date_diff($date1, $date2);
      if ($diff->format("%R%a") < 0) {
        if (abs($diff->format("%R%a")) > $grace_period) {
          return $this->applocal['payment_expired'];
        } else {
          return $this->applocal['make_payment_before'] . ($diff->format("%R%a") + $grace_period) . $this->applocal['make_payment_before_days'];
        }
      } else {
        if ($diff->format("%R%a") > 7) {
          return $this->applocal['sub_expires_on'] . $row->expiry_date;
        } else {
          return $this->applocal['sub_due'] . $row->expiry_date . ", " . $this->applocal['pay_before'] . abs($diff->format("%R%a")) . $this->applocal['days_remaining'];
        }
      }
    }
    //var_dump($row); die;
    return $row;
  }
}
