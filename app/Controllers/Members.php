<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Members_model as membersmodel;
use App\Models\MembershipForm_model as membershipformmodel;

class Members extends BaseController
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
    //$data['userRecords'] = $membersmodel->usersListing();
    return $this->view("members/listing", $this->viewdata);
  }

  function getMembers()
  {
    // Datatables Variables
    $membersmodel = new membersmodel();
    $draw = intval($_POST['draw']);
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = "";
    if (isset($_POST['search']['value'])) {
      $searchValue = $_POST['search']['value']; // Search value
    }

    $columnName = "";
    if (isset($_POST['columns'][$columnIndex]['data'])) {
      $columnSortOrder = $_POST['columns'][$columnIndex]['data']; // Search value
    }

    $columnSortOrder = "ASC";
    if (isset($_POST['order'][0]['dir'])) {
      $columnSortOrder = $_POST['order'][0]['dir']; // Search value
    }


    $feeds = $membersmodel->adminMembersListing($columnName, $columnSortOrder, $searchValue, $start, $length);
    $total_feeds = $membersmodel->get_total_members($searchValue);
    //var_dump($feeds); die;
    $dat = array();

    $count = $start + 1;
    foreach ($feeds as $r) {
      //var_dump($r); die;
      //$title = substr($r->title,0,10 );
      //$content = substr($r->content,0,50 );

      $dat[] = array(
        $count,
        $r->email,
        $r->firstname,
        $r->lastname,
        $r->age,
        '
	                <div class="dropdown">
	                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
	                    <i class="dw dw-more"></i>
	                  </a>
	                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="' . base_url() . '/viewMember/' . $r->id . '"><i class="dw dw-eye"></i> View</a>
	                    <a class="dropdown-item" href="' . base_url() . '/editMember/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
	                    <a data-type="members" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
	                    <i data-type="members" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
	                  </div>
	                </div>
	                '
      );
      $count++;
    }

    $output = array(
      "draw" => $draw,
      "recordsTotal" => $total_feeds,
      "recordsFiltered" => $total_feeds,
      "data" => $dat
    );
    echo json_encode($output);
  }

  public function newMember()
  {
    return $this->view("members/new", $this->viewdata);
  }

  public function editMember($id = 0)
  {
    $membersmodel = new membersmodel();
    $this->viewdata['member'] = $membersmodel->getMemberInfo($id);
    if (count((array)$this->viewdata['member']) == 0) {
      return redirect()->to(base_url() . '/membersListing');
    }
    return $this->view("members/edit", $this->viewdata);
  }

  public function viewMember($id = 0)
  {
    $membersmodel = new membersmodel();
    $this->viewdata['member'] = $membersmodel->getMemberInfo($id);
    if (count((array)$this->viewdata['member']) == 0) {
      return redirect()->to(base_url() . '/membersListing');
    }
    $this->viewdata['answers'] = (new membershipformmodel())->getAnswersForMember($id);
    return $this->view("members/view", $this->viewdata);
  }

  function saveNewMember()
  {
    $membersmodel = new membersmodel();
    $firstname = $this->request->getVar('firstname');
    $lastname = $this->request->getVar('lastname');
    $gender = $this->request->getVar('gender');
    $occupation = $this->request->getVar('occupation');
    $phonenumber = $this->request->getVar('phonenumber');
    $email = $this->request->getVar('email');
    $address = $this->request->getVar('address');
    $facebook = $this->request->getVar('facebook');
    $twitter = $this->request->getVar('twitter');
    $linkedln = $this->request->getVar('linkedln');
    $dob = $this->request->getVar('dob');

    $_date = \DateTime::createFromFormat("Y-m-d", $dob);
    $year =  $_date->format("Y") + 0;
    $month =  $_date->format("m") + 0;
    $day =  $_date->format("d") + 0;


    $info = array(
      'age' => $this->getAge($dob),
      'year' => $year,
      'month' => $month,
      'day' => $day,
      'dob' => $dob,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'gender' => $gender,
      'occupation' => $occupation,
      'phonenumber' => $phonenumber,
      'email' => $email,
      'address' => $address,
      'facebook' => $facebook,
      'twitter' => $twitter,
      'linkedln' => $linkedln,
      'date_inserted' => date('Y-m-d H:i:s'),
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $membersmodel->addNewMember($info);
    if ($membersmodel->status == "ok") {
      $this->session->setFlashdata('success', $membersmodel->message);
    } else {
      $this->session->setFlashdata('error', $membersmodel->message);
    }
    return redirect()->to(base_url() . '/newMember');
  }


  function editMemberData()
  {
    $membersmodel = new membersmodel();
    $id = $this->request->getVar('id');
    $firstname = $this->request->getVar('firstname');
    $lastname = $this->request->getVar('lastname');
    $gender = $this->request->getVar('gender');
    $occupation = $this->request->getVar('occupation');
    $phonenumber = $this->request->getVar('phonenumber');
    $email = $this->request->getVar('email');
    $address = $this->request->getVar('address');
    $facebook = $this->request->getVar('facebook');
    $twitter = $this->request->getVar('twitter');
    $linkedln = $this->request->getVar('linkedln');
    $dob = $this->request->getVar('dob');

    $_date = \DateTime::createFromFormat("Y-m-d", $dob);
    $year =  $_date->format("Y") + 0;
    $month =  $_date->format("m") + 0;
    $day =  $_date->format("d") + 0;


    $info = array(
      'age' => $this->getAge($dob),
      'year' => $year,
      'month' => $month,
      'day' => $day,
      'dob' => $dob,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'gender' => $gender,
      'occupation' => $occupation,
      'phonenumber' => $phonenumber,
      'email' => $email,
      'address' => $address,
      'facebook' => $facebook,
      'twitter' => $twitter,
      'linkedln' => $linkedln,
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $membersmodel->editMember($info, $id);
    if ($membersmodel->status == "ok") {
      $this->session->setFlashdata('success', $membersmodel->message);
    } else {
      $this->session->setFlashdata('error', $membersmodel->message);
    }
    return redirect()->to(base_url() . '/editMember/' . $id);
  }


  function deleteMember($id = 0)
  {
    $membersmodel = new membersmodel();
    $membersmodel->deleteMember($id);
    if ($membersmodel->status == "ok") {
      $this->session->setFlashdata('success', $membersmodel->message);
    } else {
      $this->session->setFlashdata('error', $membersmodel->message);
    }
    return redirect()->to(base_url() . '/membersListing');
  }

  function getAge($dateofbirth)
  {
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dateofbirth), date_create($today));
    return $diff->format('%y');
  }

  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/events/')) {
      mkdir('./uploads/thumbnails/events/', 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'thumbnail' => [
        'uploaded[thumbnail]',
        'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        'max_size[thumbnail,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('thumbnail');
      $img->move('./uploads/members/');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
