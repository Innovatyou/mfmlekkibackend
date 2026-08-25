<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Testimony_model as testimonymodel;

class Testimony extends BaseController
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
    if (!hasPermission('testimony.view') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $this->viewdata['testimonies'] = $testimonymodel->itemsListing();
    return $this->view("testimony/listing", $this->viewdata);
  }

  public function newTestimony()
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    return $this->view("testimony/new", $this->viewdata);
  }

  public function editTestimony($id = 0)
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $this->viewdata['testimony'] = $testimonymodel->getItemInfo($id);
    if ($this->viewdata['testimony'] == NULL) {
      return redirect()->to(base_url() . '/testimonyListing');
    }
    return $this->view("testimony/edit", $this->viewdata);
  }

  public function viewTestimony($id = 0)
  {
    if (!hasPermission('testimony.view') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $this->viewdata['testimony'] = $testimonymodel->getItemInfo($id);
    if ($this->viewdata['testimony'] == NULL) {
      return redirect()->to(base_url() . '/testimonyListing');
    }
    return $this->view("testimony/view", $this->viewdata);
  }

  function savenewtestimony()
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $title = $this->request->getVar('title');
    $testifier = $this->request->getVar('testifier');
    $content = $this->request->getVar('content');

    $info = array(
      'title' => $title,
      'content' => $content,
      'testifier' => $testifier,
      'status' => 0,
    );
    $testimonymodel->addNewItem($info);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/newTestimony');
  }


  function edittestimonydata()
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $testifier = $this->request->getVar('testifier');
    $content = $this->request->getVar('content');
    $info = array(
      'title' => $title,
      'content' => $content,
      'testifier' => $testifier,
    );


    $testimonymodel->editItem($info, $id);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/editTestimony/' . $id);
  }

  function editTestimonyStatus($id, $status)
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $info = array(
      'status' => $status,
    );
    $testimonymodel->editItem($info, $id);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/testimonyListing');
  }


  function replyTestimony()
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $id    = (int) $this->request->getVar('id');
    $reply = trim((string) $this->cleanup($this->request->getVar('reply')));

    $testimonymodel = new testimonymodel();
    $testimony = $testimonymodel->getItemInfo($id);

    if (!$testimony || $reply === '') {
      $this->session->setFlashdata('error', 'Please enter a reply.');
      return redirect()->to(base_url('viewTestimony/' . $id));
    }

    $testimonymodel->saveReply($id, $reply, $this->session->get('name') ?? 'Admin');

    if ($testimonymodel->status == "ok") {
      if (!empty($testimony->email)) {
        $this->notify_user($testimony->email, '', 'Reply to your testimony "' . $testimony->title . '": ' . $reply);
        $this->session->setFlashdata('success', 'Reply sent to the submitter.');
      } else {
        $this->session->setFlashdata('success', 'Reply saved (no linked app account to notify -- this testimony has no email on file).');
      }
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }

    return redirect()->to(base_url('viewTestimony/' . $id));
  }

  function deleteTestimony($id = 0)
  {
    if (!hasPermission('testimony.edit') && !isSuperAdmin()) {
      return $this->response->setStatusCode(403)->setBody('Access Denied');
    }
    $testimonymodel = new testimonymodel();
    $testimonymodel->deleteItem($id);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/testimonyListing');
  }
}
