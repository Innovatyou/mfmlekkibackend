<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Users_model as usersmodel;

class User extends BaseController
{
  protected $session;
  protected $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->apitoken = $this->session->get('apitoken');
  }

  public function index()
  {
    $usersmodel = new usersmodel();
    $this->viewdata['userRecords'] = $usersmodel->usersListing($this->apitoken);
    return $this->view("admin/listing", $this->viewdata);
  }

  public function newAdmin()
  {
    return $this->view("admin/new", $this->viewdata);
  }

  public function editAdmin($id = 0)
  {
    $usersmodel = new usersmodel();
    $this->viewdata['admin'] = $usersmodel->getAdminInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['admin']) == 0) {
      return redirect()->to(base_url() . '/adminListing');
    }
    return $this->view("admin/edit", $this->viewdata);
  }

  function savenewadmin()
  {
    $usersmodel = new usersmodel();
    $name = $this->request->getVar('name');
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $info = array(
      'role' => 0,
      'apitoken' => $this->apitoken,
      'fullname'       => $name,
      'email'      => $email,
      'password'   => $hashed
    );
    $usersmodel->addNewAdmin($info);
    if ($usersmodel->status == "ok") {
      $this->session->setFlashdata('success', $usersmodel->message);
    } else {
      $this->session->setFlashdata('error', $usersmodel->message);
    }
    return redirect()->to(base_url() . '/newAdmin');
  }


  function editadmindata()
  {
    $usersmodel = new usersmodel();
    $id = $this->request->getVar('id');
    $name = $this->request->getVar('name');
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');
    $info = array(
      'fullname'       => $name,
      'email'      => $email,
      //'password'   => getHashedPassword($password)
    );

    if ($password != "") {
      $info['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $usersmodel->editAdmin($info, $id, $this->apitoken);
    if ($usersmodel->status == "ok") {
      $this->session->setFlashdata('success', $usersmodel->message);
    } else {
      $this->session->setFlashdata('error', $usersmodel->message);
    }
    return redirect()->to(base_url() . '/editAdmin/' . $id);
  }


  function deleteAdmin($id = 0)
  {
    $usersmodel = new usersmodel();
    $usersmodel->deleteAdmin($id, $this->apitoken);
    if ($usersmodel->status == "ok") {
      $this->session->setFlashdata('success', $usersmodel->message);
    } else {
      $this->session->setFlashdata('error', $usersmodel->message);
    }
    return redirect()->to(base_url() . '/adminListing');
  }
}
