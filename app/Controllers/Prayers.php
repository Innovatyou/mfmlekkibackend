<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Prayer_model as prayermodel;

class Prayers extends BaseController
{
  protected $session;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();

    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    $prayermodel = new prayermodel();
    $this->viewdata['prayers'] = $prayermodel->itemsListing();
    return $this->view("prayers/listing", $this->viewdata);
  }

  public function newPrayer()
  {
    return $this->view("prayers/new", $this->viewdata);
  }

  public function editPrayer($id = 0)
  {
    $prayermodel = new prayermodel();
    $this->viewdata['prayer'] = $prayermodel->getItemInfo($id);
    if ($this->viewdata['prayer'] == NULL) {
      return redirect()->to(base_url() . '/prayers');
    }
    return $this->view("prayers/edit", $this->viewdata);
  }

  public function viewPrayer($id = 0)
  {
    $prayermodel = new prayermodel();
    $this->viewdata['prayer'] = $prayermodel->getItemInfo($id);
    if ($this->viewdata['prayer'] == NULL) {
      return redirect()->to(base_url() . '/prayers');
    }
    return $this->view("prayers/view", $this->viewdata);
  }

  function savenewprayer()
  {
    $prayermodel = new prayermodel();
    $title = $this->request->getVar('title');
    $requester = $this->request->getVar('requester');
    $content = $this->request->getVar('content');
    $public = $this->request->getVar('public');

    $info = array(
      'title' => $title,
      'content' => $content,
      'requester' => $requester,
      'public' => $public,
      'status' => 0,
    );
    $prayermodel->addNewItem($info);
    if ($prayermodel->status == "ok") {
      $this->session->setFlashdata('success', $prayermodel->message);
    } else {
      $this->session->setFlashdata('error', $prayermodel->message);
    }
    return redirect()->to(base_url() . '/newPrayer');
  }


  function editprayerdata()
  {
    $prayermodel = new prayermodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $requester = $this->request->getVar('requester');
    $content = $this->request->getVar('content');
    $public = $this->request->getVar('public');
    $info = array(
      'title' => $title,
      'content' => $content,
      'requester' => $requester,
      'public' => $public,
    );


    $prayermodel->editItem($info, $id);
    if ($prayermodel->status == "ok") {
      $this->session->setFlashdata('success', $prayermodel->message);
    } else {
      $this->session->setFlashdata('error', $prayermodel->message);
    }
    return redirect()->to(base_url() . '/editPrayer/' . $id);
  }

  function editPrayerStatus($id, $status)
  {
    $prayermodel = new prayermodel();
    $info = array(
      'status' => $status,
    );
    $prayermodel->editItem($info, $id);
    if ($prayermodel->status == "ok") {
      $this->session->setFlashdata('success', $prayermodel->message);
    } else {
      $this->session->setFlashdata('error', $prayermodel->message);
    }
    return redirect()->to(base_url() . '/prayersListing');
  }


  function deletePrayer($id = 0)
  {
    $prayermodel = new prayermodel();
    $prayermodel->deleteItem($id);
    if ($prayermodel->status == "ok") {
      $this->session->setFlashdata('success', $prayermodel->message);
    } else {
      $this->session->setFlashdata('error', $prayermodel->message);
    }
    return redirect()->to(base_url() . '/prayersListing');
  }
}
