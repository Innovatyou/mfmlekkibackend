<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Articles_model as articlesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;
//use App\Models\Home_model as homemodel;

class Articles extends BaseController
{
  protected $session;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    //$this->articlesmodel = new articlesmodel();
    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    return $this->view("articles/listing", $this->viewdata);
  }

  function getArticles()
  {
    // Datatables Variables
    $articlesmodel = new articlesmodel();
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


    $feeds = $articlesmodel->adminarticlesListing($columnName, $columnSortOrder, $searchValue, $start, $length);
    $total_feeds = $articlesmodel->get_total_articles($searchValue);
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
	                    <a class="dropdown-item" href="' . base_url() . '/editArticle/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
	                    <a data-type="articles" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
	                    <i data-type="articles" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
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

  public function newArticle()
  {
    return $this->view("articles/new", $this->viewdata);
  }

  public function editArticle($id = 0)
  {
    $articlesmodel = new articlesmodel();
    $this->viewdata['article'] = $articlesmodel->getArticleInfo($id);
    if (count((array)$this->viewdata['article']) == 0) {
      return redirect()->to(base_url() . '/articlesListing');
    }
    return $this->view("articles/edit", $this->viewdata);
  }

  function saveNewArticle()
  {
    $articlesmodel = new articlesmodel();
    $date = $this->request->getVar('date');
    $title = $this->request->getVar('title');
    $author = $this->request->getVar('author');
    $content = $this->request->getVar('content');


    $info = array(
      'date' => $date,
      'title' => $title,
      'author' => $author,
      'content' => $content
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $insertid = $articlesmodel->addNewArticle($info);
    if ($insertid != 0) {
      $itm = $articlesmodel->getArticleInfo($insertid);
      //var_dump($article); die;
      if (count((array)$itm) > 0) {
        $settingsmodel = new settingsmodel();
        $server_key = $settingsmodel->getFcmServerKey();
        $fcmmodel = new fcmmodel();
        $fcmmodel->push_item_data($server_key, $itm, "Article");
      }
    }
    if ($articlesmodel->status == "ok") {
      $this->session->setFlashdata('success', $articlesmodel->message);
    } else {
      $this->session->setFlashdata('error', $articlesmodel->message);
    }
    return redirect()->to(base_url() . '/newArticle');
  }


  function editArticleData()
  {
    $articlesmodel = new articlesmodel();
    $id = $this->request->getVar('id');
    $date = $this->request->getVar('date');
    $title = $this->request->getVar('title');
    $author = $this->request->getVar('author');
    $content = $this->request->getVar('content');


    $info = array(
      'date' => $date,
      'title' => $title,
      'author' => $author,
      'content' => $content
    );


    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      }
    }

    $articlesmodel->editArticle($info, $id);
    if ($articlesmodel->status == "ok") {
      $this->session->setFlashdata('success', $articlesmodel->message);
    } else {
      $this->session->setFlashdata('error', $articlesmodel->message);
    }
    return redirect()->to(base_url() . '/editArticle/' . $id);
  }


  function deleteArticle($id = 0)
  {
    $articlesmodel = new articlesmodel();
    $articlesmodel->deleteArticle($id);
    if ($articlesmodel->status == "ok") {
      $this->session->setFlashdata('success', $articlesmodel->message);
    } else {
      $this->session->setFlashdata('error', $articlesmodel->message);
    }
    return redirect()->to(base_url() . '/articlesListing');
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
      //$this->viewdata = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('thumbnail');
      $img->move('./uploads/thumbnails/');
      $this->viewdata = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
