<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Testimony_model as testimonymodel;

class Testimony extends BaseController
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
    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    $testimonymodel = new testimonymodel();
    $this->viewdata['testimonies'] = $testimonymodel->itemsListing($this->apitoken);
    return $this->view("testimony/listing", $this->viewdata);
  }

  public function newTestimony()
  {
    return $this->view("testimony/new", $this->viewdata);
  }

  public function editTestimony($id = 0)
  {
    $testimonymodel = new testimonymodel();
    $this->viewdata['testimony'] = $testimonymodel->getItemInfo($id, $this->apitoken);
    if ($this->viewdata['testimony'] == NULL) {
      return redirect()->to(base_url() . '/testimonyListing');
    }
    return $this->view("testimony/edit", $this->viewdata);
  }

  function savenewtestimony()
  {
    $testimonymodel = new testimonymodel();
    $title = $this->request->getVar('title');
    $testifier = $this->request->getVar('testifier');
    $content = $this->request->getVar('content');

    $info = array(
      'title' => $title,
      'apitoken' => $this->apitoken,
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


    $testimonymodel->editItem($info, $id, $this->apitoken);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/editTestimony/' . $id);
  }

  function editTestimonyStatus($id, $status)
  {
    $testimonymodel = new testimonymodel();
    $info = array(
      'status' => $status,
    );
    $testimonymodel->editItem($info, $id, $this->apitoken);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/testimonyListing');
  }


  function deleteTestimony($id = 0)
  {
    $testimonymodel = new testimonymodel();
    $testimonymodel->deleteItem($id, $this->apitoken);
    if ($testimonymodel->status == "ok") {
      $this->session->setFlashdata('success', $testimonymodel->message);
    } else {
      $this->session->setFlashdata('error', $testimonymodel->message);
    }
    return redirect()->to(base_url() . '/testimonyListing');
  }
}
