<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Radio_model as radiomodel;

class Radio extends BaseController
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
    $radiomodel = new radiomodel();
    $this->viewdata['radio'] = $radiomodel->radioListing();
    return $this->view("radio/listing", $this->viewdata);
  }

  public function newRadio()
  {
    return $this->view("radio/new", $this->viewdata);
  }

  public function editRadio($id = 0)
  {
    $radiomodel = new radiomodel();
    $this->viewdata['radio'] = $radiomodel->getRadioInfo($id);
    if (count((array)$this->viewdata['radio']) == 0) {
      return redirect()->to(base_url() . '/radio');
    }
    return $this->view("radio/edit", $this->viewdata);
  }

  function savenewradio()
  {
    $radiomodel = new radiomodel();
    if (empty($_FILES['thumbnail']['name'])) {
      $this->session->setFlashdata('error', "Thumbnail is empty");
      return redirect()->to(base_url() . '/newRadio');
    }
    $thumb_upload = $this->upload_thumbnail();
    if ($thumb_upload[0] == 'error') {
      $this->session->setFlashdata('error', "\nThumbnail upload error: " . $thumb_upload[1]['thumbnail']);
      return redirect()->to(base_url() . '/newRadio');
      exit;
    }
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $link = $this->request->getVar('link');
    $status = $this->request->getVar('status');
    $info = array(
      'title' => $title,
      'description' => $description,
      'link' => $link,
      'status' => $status,
      'cover_photo' => $thumb_upload[1],
    );
    $radiomodel->addNewRadio($info);
    if ($radiomodel->status == "ok") {
      $this->session->setFlashdata('success', $radiomodel->message);
    } else {
      $this->session->setFlashdata('error', $radiomodel->message);
    }
    return redirect()->to(base_url() . '/newRadio');
  }


  function editRadioData()
  {
    $radiomodel = new radiomodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $link = $this->request->getVar('link');
    $status = $this->request->getVar('status');
    $info = array(
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
        return redirect()->to(base_url() . '/editRadio/' . $id);
        return;
      }
    }

    $radiomodel->editRadio($info, $id);
    if ($radiomodel->status == "ok") {
      $this->session->setFlashdata('success', $radiomodel->message);
    } else {
      $this->session->setFlashdata('error', $radiomodel->message);
    }
    return redirect()->to(base_url() . '/editRadio/' . $id);
  }


  function deleteRadio($id = 0)
  {
    $radiomodel = new radiomodel();
    $radiomodel->deleteRadio($id);
    if ($radiomodel->status == "ok") {
      $this->session->setFlashdata('success', $radiomodel->message);
    } else {
      $this->session->setFlashdata('error', $radiomodel->message);
    }
    return redirect()->to(base_url() . '/radio');
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
