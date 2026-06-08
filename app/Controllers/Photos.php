<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Photos_model as photosmodel;

class Photos extends BaseController
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
    $photosmodel = new photosmodel();
    $this->viewdata['photos'] = $photosmodel->photosListing($this->apitoken);
    return $this->view("photos/listing", $this->viewdata);
  }

  public function newPhotos()
  {
    return $this->view("photos/new", $this->viewdata);
  }

  public function editPhoto($id = 0)
  {
    $photosmodel = new photosmodel();
    $this->viewdata['photo'] = $photosmodel->getPhotoInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['photo']) == 0) {
      return redirect()->to(base_url() . '/photos');
    }
    return $this->view("photos/edit", $this->viewdata);
  }

  function savenewphoto()
  {
    $photosmodel = new photosmodel();
    $upload_files = [];
    if (!file_exists('./uploads/photos/' . $this->apitoken)) {
      mkdir('./uploads/photos/' . $this->apitoken, 0777, true);
    }
    if ($this->request->getFileMultiple('file')) {
      foreach ($this->request->getFileMultiple('file') as $file) {
        $file->move('./uploads/photos/' . $this->apitoken);
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
      'apitoken' => $this->apitoken,
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
    $photosmodel->editPhoto($info, $id, $this->apitoken);
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
    $photosmodel->deletePhoto($id, $this->apitoken);
    if ($photosmodel->status == "ok") {
      $this->session->setFlashdata('success', $photosmodel->message);
    } else {
      $this->session->setFlashdata('error', $photosmodel->message);
    }
    return redirect()->to(base_url() . '/photos');
  }
}
