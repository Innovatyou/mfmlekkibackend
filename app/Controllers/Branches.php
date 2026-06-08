<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Branches extends BaseController
{
  protected $session;
  protected $apitoken;


  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->apitoken = $this->session->get('apitoken');
  }

  //fetch audios/videos
  function church_branches()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $branchesmodel = new branchesmodel();
    $results = $branchesmodel->fetch_branches($page, $this->apitoken);
    $total_items = $branchesmodel->get_total_branches($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;

    echo json_encode(array("status" => "ok", "branches" => $results, "isLastPage" => $isLastPage));
  }

  public function index()
  {
    $branchesmodel = new branchesmodel();
    $this->viewdata['branches'] = $branchesmodel->branchesListing($this->apitoken);
    return $this->view("branches/listing", $this->viewdata);
  }

  public function loadbranches()
  {
    $branchesmodel = new branchesmodel();
    $branches = $branchesmodel->branchesListing($this->apitoken);
    echo json_encode(array("status" => "ok", "branches" => $branches));
  }

  public function newBranch()
  {
    return $this->view("branches/new", $this->viewdata);
  }

  public function editBranch($id = 0)
  {
    $branchesmodel = new branchesmodel();
    $this->viewdata['branch'] = $branchesmodel->getBranchInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['branch']) == 0) {
      return redirect()->to(base_url() . '/branchesListing');
    }
    return $this->view("branches/edit", $this->viewdata);
  }

  function savenewbranch()
  {
    $name = $this->request->getVar('name');
    $phone = $this->request->getVar('phone');
    $email = $this->request->getVar('email');
    $address = $this->request->getVar('address');
    $pastor = $this->request->getVar('pastor');
    $latitude = $this->request->getVar('latitude');
    $longitude = $this->request->getVar('longitude');
    $info = array(
      'apitoken' => $this->apitoken,
      'name' => $name,
      'phone' => $phone,
      'email' => $email,
      'address' => $address,
      'pastor' => $pastor,
      'latitude' => $latitude,
      'longitude' => $longitude
    );
    $branchesmodel = new branchesmodel();
    $branchesmodel->addNewBranch($info);
    if ($branchesmodel->status == "ok") {
      $this->session->setFlashdata('success', $branchesmodel->message);
    } else {
      $this->session->setFlashdata('error', $branchesmodel->message);
    }
    //redirect('newBranch');
    return redirect()->to(base_url() . '/newBranch');
  }


  function editBranchData()
  {
    $branchesmodel = new branchesmodel();
    $id = $this->request->getVar('id');
    $name = $this->request->getVar('name');
    $phone = $this->request->getVar('phone');
    $email = $this->request->getVar('email');
    $address = $this->request->getVar('address');
    $pastor = $this->request->getVar('pastor');
    $latitude = $this->request->getVar('latitude');
    $longitude = $this->request->getVar('longitude');
    $info = array(
      'name' => $name,
      'phone' => $phone,
      'email' => $email,
      'address' => $address,
      'pastor' => $pastor,
      'latitude' => $latitude,
      'longitude' => $longitude
    );

    $branchesmodel->editBranch($info, $id, $this->apitoken);
    if ($branchesmodel->status == "ok") {
      $this->session->setFlashdata('success', $branchesmodel->message);
    } else {
      $this->session->setFlashdata('error', $branchesmodel->message);
    }
    return redirect()->to(base_url() . '/editBranch/' . $id);
    //redirect('editBranch/'.$id);
  }


  function deleteBranch($id = 0)
  {
    $branchesmodel = new branchesmodel();
    $branchesmodel->deleteBranch($id, $this->apitoken);
    if ($branchesmodel->status == "ok") {
      $this->session->setFlashdata('success', $branchesmodel->message);
    } else {
      $this->session->setFlashdata('error', $branchesmodel->message);
    }
    return redirect()->to(base_url() . '/branchesListing');
    //redirect('branchesListing');
  }
}
