<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Livestream_model as livestreammodel;

class Livestream extends BaseController
{
  protected $session;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
  }

  public function index()
  {
    $livestreammodel = new livestreammodel();
    $this->viewdata['livestreams'] = $livestreammodel->livestreamsListing();
    return $this->view("livestream/listing", $this->viewdata);
  }

  public function newLivestream()
  {
    return $this->view("livestream/new", $this->viewdata);
  }

  public function editLivestream($id = 0)
  {
    $livestreammodel = new livestreammodel();
    $this->viewdata['livestream'] = $livestreammodel->getLivestreamInfo($id);
    if (count((array)$this->viewdata['livestream']) == 0) {
      return redirect()->to(base_url() . '/livestream');
    }
    return $this->view("livestream/edit", $this->viewdata);
  }

  function savenewlivestream()
  {
    $livestreammodel = new livestreammodel();
    $cover = '';
    if (!empty($_FILES['thumbnail']['name'])) {
      $thumb_upload = $this->upload_thumbnail();
      if ($thumb_upload[0] == 'error') {
        $this->session->setFlashdata('error', "\nThumbnail upload error: " . $thumb_upload[1]['thumbnail']);
        return redirect()->to(base_url() . '/newLivestream');
        exit;
      }
      $cover = $thumb_upload[1];
    }

    $source = $this->request->getVar('source');
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $link = (string) $this->request->getVar('link'); // Force string type
    $status = $this->request->getVar('status');
    
    // Validate stream URL
    $rules = [
      'link' => 'permit_empty|string',
    ];
    
    if (!$this->validate($rules)) {
      $this->session->setFlashdata('error', 'Invalid stream URL. Must be a string.');
      return redirect()->to(base_url() . '/newLivestream');
    }
    
    $info = array(
      'title' => $title,
      'source' => $source,
      'description' => $description,
      'link' => $link,
      'status' => $status,
      'cover_photo' => $cover,
    );
    $livestreammodel->addNewLivestream($info);
    if ($livestreammodel->status == "ok") {
      $this->session->setFlashdata('success', $livestreammodel->message);
    } else {
      $this->session->setFlashdata('error', $livestreammodel->message);
    }
    return redirect()->to(base_url() . '/newLivestream');
  }


  function editLivestreamData()
  {
    $livestreammodel = new livestreammodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $link = (string) $this->request->getVar('link'); // Force string type
    $status = $this->request->getVar('status');
    $source = $this->request->getVar('source');
    
    // Validate stream URL
    $rules = [
      'link' => 'permit_empty|string',
    ];
    
    if (!$this->validate($rules)) {
      $this->session->setFlashdata('error', 'Invalid stream URL. Must be a string.');
      return redirect()->to(base_url() . '/editLivestream/' . $id);
    }
    
    $info = array(
      'source' => $source,
      'title' => $title,
      'description' => $description,
      'link' => $link,
      'status' => $status,
    );
    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();

      if ($upload[0] == 'ok') {
        $info['cover_photo'] = $upload[1];
      } else {
        $this->session->setFlashdata('error', "\nThumbnail upload error: " . $upload[1]['thumbnail']);
        return redirect()->to(base_url() . '/editLivestream/' . $id);
        return;
      }
    }

    $livestreammodel->editLivestream($info, $id);
    if ($livestreammodel->status == "ok") {
      $this->session->setFlashdata('success', $livestreammodel->message);
    } else {
      $this->session->setFlashdata('error', $livestreammodel->message);
    }
    return redirect()->to(base_url() . '/editLivestream/' . $id);
  }


  function deleteLivestream($id = 0)
  {
    $id = intval($id);
    $livestreammodel = new livestreammodel();
    if ($id <= 0) {
      $this->session->setFlashdata('error', 'Invalid livestream ID.');
      return redirect()->to(base_url() . '/livestreams');
    }
    $livestreammodel->deleteLivestream($id);
    if ($livestreammodel->status == "ok") {
      $this->session->setFlashdata('success', $livestreammodel->message);
    } else {
      $this->session->setFlashdata('error', $livestreammodel->message);
    }
    return redirect()->to(base_url() . '/livestreams');
  }

  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/')) {
      mkdir('./uploads/thumbnails/', 0777, true);
    }
    helper(['form', 'url']);
    // If no file was provided, consider it okay; return empty name
    if (empty($_FILES['thumbnail']['name'])) {
      return ['ok', ''];
    }

    $input = $this->validate([
      'thumbnail' => [
        'uploaded[thumbnail]',
        'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        'max_size[thumbnail,10024]',
      ]
    ]);
    if (!$input) {
      return ['error', $this->validator->getErrors()];
    }

    $img = $this->request->getFile('thumbnail');
    $img->move('./uploads/thumbnails/');
    return ['ok', $img->getName()];
  }
}
