<?php

namespace App\Models;

use App\Models\Basemodel;

class Account_model extends BaseModel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  //authenticate user email and password
  public function authenticateUser($email, $password, $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $user = $query->getRow(0);
    if (!$user) {
      $this->status = $this->applocal['error'];
      $this->message =  $this->applocal['email_pass_nonexist'];
    } else {
      //then we verify if password matches the saved hashed password
      if (password_verify($password, $user->password)) {
        if ($user->verified != 1) {
          //if user have not verified his account, we display message for user to verify his email address
          $this->status = $this->applocal['error'];
          $this->message = $this->applocal['verification_sent'];
        } else {
          $this->status = $this->applocal['ok'];
          $this->message = $this->applocal['auth_user'];
        }
      } else {
        $this->status = $this->applocal['error'];
        $this->message = $this->applocal['email_pass_incorrect'];
      }
    }
    if ($user) {
      if ($user->thumbnail != "") {
        $user->thumbnail = base_url() . "/uploads/members/" . $apitoken . "/" . $user->thumbnail;
      }
      if ($user->coverphoto != "") {
        $user->coverphoto = base_url() . "/uploads/members/" . $apitoken . "/" . $user->coverphoto;
      }
    }
    return $user;
  }

  //create email or password for user
  public function createAccount($email, $password, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $user = $query->getRow(0);
    if ($user) {
      if ($user->password != "") {
        $this->status = $this->applocal['error'];
        $this->message = $this->applocal['account_exist'];
      } else {
        $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
        $builder->where('email', $email);
        $builder->where('apitoken', $apitoken);
        $builder->update($info);
        $this->status = $this->applocal['ok'];
      }
    } else {
      $info = array('apitoken' => $apitoken, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'verified' => 1);
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
    }
  }

  //update user verification status
  public function updateUserVerfication($email, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $info = array('verified' => 1);
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  //update user password
  public function updateUserPassword($email, $password, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  //update user profile
  public function updateUserProfile($info, $email, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  public function verifyEmailExists($email, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  //authenticate user email and password
  public function getUpdatedUserProfile($email, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $user = $query->getRow(0);
    if ($user) {
      if ($user->thumbnail != "") {
        $user->thumbnail = base_url() . "/uploads/members/" . $apitoken . "/" . $user->thumbnail;
      }
      if ($user->coverphoto != "") {
        $user->coverphoto = base_url() . "/uploads/members/" . $apitoken . "/" . $user->coverphoto;
      }
    }
    return $user;
  }

  function deletemyaccount($email, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('email', $email);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['delete_success'];
  }
}
