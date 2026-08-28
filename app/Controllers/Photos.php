<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Photos_model as photosmodel;

class Photos extends BaseController
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
    $photosmodel = new photosmodel();
    $this->viewdata['photos'] = $photosmodel->photosListing();
    return $this->view("photos/listing", $this->viewdata);
  }

  public function newPhotos()
  {
    return $this->view("photos/new", $this->viewdata);
  }

  public function editPhoto($id = 0)
  {
    $photosmodel = new photosmodel();
    $this->viewdata['photo'] = $photosmodel->getPhotoInfo($id);
    if (count((array)$this->viewdata['photo']) == 0) {
      return redirect()->to(base_url() . '/photos');
    }
    return $this->view("photos/edit", $this->viewdata);
  }

  function savenewphoto()
  {
    $photosmodel = new photosmodel();
    $upload_files = [];
    if (!file_exists('./uploads/photos/')) {
      mkdir('./uploads/photos/', 0777, true);
    }
    if ($this->request->getFileMultiple('file')) {
      foreach ($this->request->getFileMultiple('file') as $file) {
        $file->move('./uploads/photos/');
        $data = [
          'name' =>  $file->getClientName(),
          'type'  => $file->getClientMimeType()
        ];
        array_push($upload_files, $file->getName());
      }
    }

    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $info = array(
      'description' => $description,
      'title' => $title,
      'thumbnail' => json_encode($upload_files)
    );
    //var_dump($info); die;
    $photosmodel->addNewPhoto($info);
    echo $photosmodel->message;
  }

  function editPhotoData()
  {
    $photosmodel = new photosmodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $info = array(
      'description' => $description,
      'title' => $title,
    );
    $photosmodel->editPhoto($info, $id);
    if ($photosmodel->status == "ok") {
      $this->session->setFlashdata('success', $photosmodel->message);
    } else {
      $this->session->setFlashdata('error', $photosmodel->message);
    }
    return redirect()->to(base_url() . '/photos');
  }

  function deletePhoto($id = 0)
  {
    $photosmodel = new photosmodel();
    $photosmodel->deletePhoto($id);
    if ($photosmodel->status == "ok") {
      $this->session->setFlashdata('success', $photosmodel->message);
    } else {
      $this->session->setFlashdata('error', $photosmodel->message);
    }
    return redirect()->to(base_url() . '/photos');
  }

  function bulkDeletePhotos()
  {
    $data = $this->get_data();
    $ids = isset($data->ids) ? $data->ids : [];
    if (!is_array($ids) || count($ids) == 0) {
      echo json_encode(array("status" => "error", "msg" => "No albums selected."));
      exit;
    }

    $photosmodel = new photosmodel();
    $deleted = 0;
    foreach ($ids as $id) {
      $id = intval($id);
      if ($id <= 0) continue;
      $photosmodel->deletePhoto($id);
      $deleted++;
    }

    echo json_encode(array("status" => "ok", "msg" => $deleted . ' album(s) deleted.'));
    exit;
  }
}
