<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Devotionals_model as devotionalsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;
//use App\Models\Home_model as homemodel;

class Devotionals extends BaseController
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
    //$data['userRecords'] = $devotionalsmodel->usersListing();
    return $this->view("devotionals/listing", $this->viewdata);
  }

  function getDevotionals()
  {
    // Datatables Variables
    $devotionalsmodel = new devotionalsmodel();
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


    $feeds = $devotionalsmodel->adminDevotionalsListing($columnName, $columnSortOrder, $searchValue, $start, $length);
    $total_feeds = $devotionalsmodel->get_total_devotionals($searchValue);
    //var_dump($feeds); die;
    $dat = array();

    $count = $start + 1;
    foreach ($feeds as $r) {
      //var_dump($r); die;
      //$title = substr($r->title,0,10 );
      //$content = substr($r->content,0,50 );

      $dat[] = array(
        $count,
        $r->date,
        $r->title,
        '
	                <div class="dropdown">
	                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
	                    <i class="dw dw-more"></i>
	                  </a>
	                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
	                    <a class="dropdown-item" href="' . base_url() . '/editDevotional/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
	                    <a data-type="devotionals" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
	                    <i data-type="devotionals" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
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

  public function newDevotional()
  {
    return $this->view("devotionals/new", $this->viewdata);
  }

  public function editDevotional($id = 0)
  {
    $devotionalsmodel = new devotionalsmodel();
    $this->viewdata['devotional'] = $devotionalsmodel->getDevotionalInfo($id);
    if (count((array)$this->viewdata['devotional']) == 0) {
      return redirect()->to(base_url() . '/devotionalsListing');
    }
    return $this->view("devotionals/edit", $this->viewdata);
  }

  function saveNewDevotional()
  {
    $devotionalsmodel = new devotionalsmodel();
    $date = $this->request->getVar('date');
    $title = $this->request->getVar('title');
    $author = $this->request->getVar('author');
    $bible_reading = $this->request->getVar('bible_reading');
    $content = $this->request->getVar('content');
    $studies = $this->request->getVar('studies');
    $confession = $this->request->getVar('confession');

    $_date = \DateTime::createFromFormat("Y-m-d", $date);
    $year =  $_date->format("Y") + 0;
    $month =  $_date->format("m") + 0;
    $day =  $_date->format("d") + 0;


    $info = array(
      'year' => $year,
      'month' => $month,
      'day' => $day,
      'date' => $date,
      'title' => $title,
      'author' => $author,
      'bible_reading' => $bible_reading,
      'studies' => $studies,
      'confession' => $confession,
      'content' => $content
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $insertid = $devotionalsmodel->addNewDevotional($info);
    if ($insertid != 0) {
      $itm = $devotionalsmodel->getDevotionalInfo($insertid);
      //var_dump($article); die;
      if (count((array)$itm) > 0) {
        $settingsmodel = new settingsmodel();
        $server_key = $settingsmodel->getFcmServerKey();
        $fcmmodel = new fcmmodel();
        $fcmmodel->push_item_data($server_key, $itm, "Devotional");
      }
    }
    if ($devotionalsmodel->status == "ok") {
      $this->session->setFlashdata('success', $devotionalsmodel->message);
    } else {
      $this->session->setFlashdata('error', $devotionalsmodel->message);
    }
    return redirect()->to(base_url() . '/newDevotional');
  }


  function editDevotionalData()
  {
    $devotionalsmodel = new devotionalsmodel();
    $id = $this->request->getVar('id');
    $date = $this->request->getVar('date');
    $title = $this->request->getVar('title');
    $author = $this->request->getVar('author');
    $bible_reading = $this->request->getVar('bible_reading');
    $content = $this->request->getVar('content');
    $studies = $this->request->getVar('studies');
    $confession = $this->request->getVar('confession');

    $_date = \DateTime::createFromFormat("Y-m-d", $date);
    $year =  $_date->format("Y") + 0;
    $month =  $_date->format("m") + 0;
    $day =  $_date->format("d") + 0;


    $info = array(
      'year' => $year,
      'month' => $month,
      'day' => $day,
      'date' => $date,
      'title' => $title,
      'author' => $author,
      'bible_reading' => $bible_reading,
      'studies' => $studies,
      'confession' => $confession,
      'content' => $content
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $devotionalsmodel->editDevotional($info, $id);
    if ($devotionalsmodel->status == "ok") {
      $this->session->setFlashdata('success', $devotionalsmodel->message);
    } else {
      $this->session->setFlashdata('error', $devotionalsmodel->message);
    }
    return redirect()->to(base_url() . '/editDevotional/' . $id);
  }


  function deleteDevotional($id = 0)
  {
    $devotionalsmodel = new devotionalsmodel();
    $devotionalsmodel->deleteDevotional($id);
    if ($devotionalsmodel->status == "ok") {
      $this->session->setFlashdata('success', $devotionalsmodel->message);
    } else {
      $this->session->setFlashdata('error', $devotionalsmodel->message);
    }
    return redirect()->to(base_url() . '/devotionalsListing');
  }

  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/')) {
      mkdir('./uploads/thumbnails/', 0777, true);
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
      $img->move('./uploads/thumbnails/');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
