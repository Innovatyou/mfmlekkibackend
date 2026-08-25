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
    if (!hasPermission('prayers.view') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $prayermodel = new prayermodel();
    $this->viewdata['prayers'] = $prayermodel->itemsListing();
    return $this->view("prayers/listing", $this->viewdata);
  }

  public function newPrayer()
  {
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    return $this->view("prayers/new", $this->viewdata);
  }

  public function editPrayer($id = 0)
  {
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $prayermodel = new prayermodel();
    $this->viewdata['prayer'] = $prayermodel->getItemInfo($id);
    if ($this->viewdata['prayer'] == NULL) {
      return redirect()->to(base_url() . '/prayersListing');
    }
    return $this->view("prayers/edit", $this->viewdata);
  }

  public function viewPrayer($id = 0)
  {
    if (!hasPermission('prayers.view') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $prayermodel = new prayermodel();
    $this->viewdata['prayer'] = $prayermodel->getItemInfo($id);
    if ($this->viewdata['prayer'] == NULL) {
      return redirect()->to(base_url() . '/prayersListing');
    }
    return $this->view("prayers/view", $this->viewdata);
  }

  function savenewprayer()
  {
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
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
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
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
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
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


  function replyPrayer()
  {
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $id    = (int) $this->request->getVar('id');
    $reply = trim((string) $this->cleanup($this->request->getVar('reply')));

    $prayermodel = new prayermodel();
    $prayer = $prayermodel->getItemInfo($id);

    if (!$prayer || $reply === '') {
      $this->session->setFlashdata('error', 'Please enter a reply.');
      return redirect()->to(base_url('viewPrayer/' . $id));
    }

    $prayermodel->saveReply($id, $reply, $this->session->get('name') ?? 'Admin');

    if ($prayermodel->status == "ok") {
      if (!empty($prayer->email)) {
        $this->notify_user($prayer->email, '', 'Reply to your prayer request "' . $prayer->title . '": ' . $reply);
        $this->session->setFlashdata('success', 'Reply sent to the requester.');
      } else {
        $this->session->setFlashdata('success', 'Reply saved (no linked app account to notify -- this request has no email on file).');
      }
    } else {
      $this->session->setFlashdata('error', $prayermodel->message);
    }

    return redirect()->to(base_url('viewPrayer/' . $id));
  }

  function deletePrayer($id = 0)
  {
    if (!hasPermission('prayers.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
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
