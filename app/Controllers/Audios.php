<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use Khill\Duration\Duration;
use App\Models\Audio_model as audiomodel;

class Audios extends BaseController
{
  protected $session;
  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    //$this->session = session();
    //$this->mediamodel = new mediamodel();
    $this->session = session();
    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    return $this->view("media/audiolisting", $this->viewdata);
  }

  function fetch()
  {
    // Datatables Variables
    $audiomodel = new audiomodel();
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


    $audios = $audiomodel->audioListing($columnName, $columnSortOrder, $searchValue, $start, $length);
    $total_audios = $audiomodel->get_total_audios($searchValue);
    //var_dump($users); die;
    $dat = array();

    $count = $start + 1;
    foreach ($audios as $r) {
      $dat[] = array(
        $count, //'.site_url()."stream?m=".$r->id.'
        '<audio controls preload="none">
                  <source src="' . $r->source . '" type="audio/mpeg">
                Your browser does not support the audio element.
                </audio>',
        $r->title,
        $r->description,
        '
                <div class="dropdown">
                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="' . base_url() . '/editAudio/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
                    <a data-type="audio" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
                    <i data-type="audio" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
                  </div>
                </div>
                '
      );
      $count++;
    }

    $output = array(
      "draw" => $draw,
      "recordsTotal" => $total_audios,
      "recordsFiltered" => $total_audios,
      "data" => $dat
    );
    echo json_encode($output);
  }

  public function newAudio()
  {
    return $this->view("media/newaudio", $this->viewdata);
  }

  public function editAudio($id = 0)
  {
    $audiomodel = new audiomodel();
    $this->viewdata['audio'] = $audiomodel->getAudioInfo($id);
    if (count((array)$this->viewdata['audio']) == 0) {
      return redirect()->to(base_url() . '/audios');
    }
    $_duration = new Duration;
    $this->viewdata['audio']->duration = $_duration->formatted(($this->viewdata['audio']->duration) / 1000);
    return $this->view("media/editaudio", $this->viewdata);
  }

  public function saveNewAudio()
  {
    $audiomodel = new audiomodel();
    $data = $this->get_data();
    if (isset($data) && isset($data->title)) {
      $media_type = 0;
      if (isset($data->media_type)) {
        $media_type = $data->media_type;
      }
      $title = $data->title;
      $category = 0;
      $subcategory = 0;

      $description = "";
      if (isset($data->description)) {
        $description = $data->description;
      }
      $duration = 0;
      if (isset($data->duration)) {
        $duration = $data->duration;
      }
      $is_free = 1;
      if (isset($data->is_free)) {
        $is_free = $data->is_free;
      }
      $can_download = 1;
      if (isset($data->can_download)) {
        $can_download = $data->can_download;
      }
      $can_preview = 1;
      $preview_duration = 0;

      $_duration = new Duration;
      $info = array(
        'category' => $category,
        'title' => $title,
        'description' => $description,
        'is_free' => $is_free,
        'can_download' => $can_download,
        'can_preview' => $can_preview,
        'preview_duration' => $preview_duration,
        'sub_category' => $subcategory,
        'duration' => $_duration->toSeconds($duration) * 1000,
        'type' => 'audio'
      );

      if ($media_type == 0) {
        //upload image file
        $thumb_upload = $this->upload_thumbnail();
        $audio_upload = $this->upload_audio();

        //var_dump($audio_upload); die;
        //echo json_encode(array("status" => "error","msg" => $audio_upload)); die;
        //upload video file


        //if there are any error, display to user
        if ($audio_upload[0] == 'error' || $thumb_upload[0] == 'error') {
          $msg = $audio_upload[0] == 'error' ? "Audio upload error: " . $audio_upload[1]['audio'] : "";
          $msg .= $thumb_upload[0] == 'error' ? "\nThumbnail upload error: " . $thumb_upload[1]['thumbnail'] : "";
          echo json_encode(array("status" => "error", "msg" => $msg));
          exit;
        }

        $info['cover_photo'] = $thumb_upload[1];
        $info['source'] = $audio_upload[1];
      } else {
        $info['cover_photo'] = $data->thumbnail_link;
        $info['source'] = $data->media_link;
      }

      $audiomodel->addNewAudio($info);
    }
    echo json_encode(array("status" => $audiomodel->status, "msg" => $audiomodel->message));
    exit;
  }

  public function editAudioData()
  {
    $audiomodel = new audiomodel();
    $data = $this->get_data();
    if (!isset($data) || !isset($data->title)) {
      echo json_encode(array("status" => $audiomodel->status, "msg" => $audiomodel->message));
      exit;
    }

    $id = isset($data->id) ? $data->id : 0;
    $title = $data->title;
    $description = "";
    if (isset($data->description)) {
      $description = $data->description;
    }

    $duration = 0;
    if (isset($data->duration)) {
      $duration = $data->duration;
    }

    $_duration = new Duration;
    $info = array(
      'title' => $title,
      'description' => $description,
      'duration' =>  $_duration->toSeconds($duration) * 1000,
    );

    $audiomodel->editAudioData($info, $id);

    echo json_encode(array("status" => $audiomodel->status, "msg" => $audiomodel->message));
    exit;
  }

  function deleteAudio($id = 0)
  {
    $audiomodel = new audiomodel();
    $audio = $audiomodel->getAudioInfo($id);
    if (count((array)$audio) > 0) {
      @unlink('./uploads/audios/' . $audio->source);
      @unlink('./uploads/thumbnails/' . $audio->cover_photo);
    }
    $audiomodel->deleteAudio($id);
    if ($audiomodel->status == "ok") {
      $this->session->setFlashdata('success', $audiomodel->message);
    } else {
      $this->session->setFlashdata('error', $audiomodel->message);
    }
    return redirect()->to(base_url() . '/audios');
  }

  public function upload_audio()
  {
    if (!file_exists('./uploads/audios/')) {
      mkdir('./uploads/audios/', 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'audio' => [
        'uploaded[audio]',
        'mime_in[audio,mp3,audio/mpeg,audio/mpg,audio/mpeg3,audio/mp3,application/octet-stream,]',
        'max_size[audio,100000]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      //var_dump($data);
      return ['error', $this->validator->getErrors()];
    } else {

      $img = $this->request->getFile('audio');
      $img->move('./uploads/audios/');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      //var_dump($data);
      return ['ok', $img->getName()];
    }
  }

  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/')) {
      mkdir('./uploads/thumbnails/', 0777, true);
    }
    helper(['form', 'url']);
    // If no file provided, consider it optional
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
