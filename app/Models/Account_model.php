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
  public function authenticateUser($email, $password)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
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
          $user->api_token = $this->ensureApiToken($email, $user->api_token ?? null);
        }
      } else {
        $this->status = $this->applocal['error'];
        $this->message = $this->applocal['email_pass_incorrect'];
      }
    }
    if ($user) {
      if ($user->thumbnail != "") {
        $user->thumbnail = $this->request_base_url() . "/uploads/members/" . $user->thumbnail;
      }
      if ($user->coverphoto != "") {
        $user->coverphoto = $this->request_base_url() . "/uploads/members/" . $user->coverphoto;
      }
    }
    return $user;
  }

  // returns the member's existing api_token, generating and persisting one if absent
  public function ensureApiToken($email, $existingToken = null)
  {
    if (!empty($existingToken)) {
      return $existingToken;
    }
    $token = bin2hex(random_bytes(32));
    $db = \Config\Database::connect("default");
    $db->table('tbl_members')->where('email', $email)->update(['api_token' => $token]);
    return $token;
  }

  // resolves a member by their mobile api_token; used by the MobileTokenAuth filter
  public function getUserByApiToken($token)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('email, api_token');
    $builder->where('api_token', $token);
    $query = $builder->get();
    return $query->getRow(0);
  }

  //create email or password for user
  public function createAccount($email, $password)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $query = $builder->get();
    $user = $query->getRow(0);
    if ($user) {
      if ($user->password != "") {
        $this->status = $this->applocal['error'];
        $this->message = $this->applocal['account_exist'];
      } else {
        $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
        $builder->where('email', $email);
        $builder->update($info);
        $this->status = $this->applocal['ok'];
      }
    } else {
      $info = array('email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'verified' => 1);
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
    }
  }

  //update user verification status
  public function updateUserVerfication($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $info = array('verified' => 1);
    $builder->where('email', $email);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  //update user password
  public function updateUserPassword($email, $password)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
    $builder->where('email', $email);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  //update user profile
  public function updateUserProfile($info, $email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('email', $email);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
  }

  public function verifyEmailExists($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  //authenticate user email and password
  public function getUpdatedUserProfile($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('email', $email);
    $query = $builder->get();
    $user = $query->getRow(0);
    if ($user) {
      if ($user->thumbnail != "") {
        $user->thumbnail = $this->request_base_url() . "/uploads/members/" . $user->thumbnail;
      }
      if ($user->coverphoto != "") {
        $user->coverphoto = $this->request_base_url() . "/uploads/members/" . $user->coverphoto;
      }
    }
    return $user;
  }

  function deletemyaccount($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('email', $email);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['delete_success'];
  }
}
