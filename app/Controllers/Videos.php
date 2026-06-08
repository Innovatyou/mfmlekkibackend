<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use Khill\Duration\Duration;
use App\Models\Video_model as videomodel;
use App\Models\Branches_model as branchesmodel;

class Videos extends BaseController
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
    return $this->view("media/videolisting", $this->viewdata);
  }

  function fetch()
  {
    // Datatables Variables
    $videomodel = new videomodel();
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


    $audios = $videomodel->videoListing($columnName, $columnSortOrder, $searchValue, $start, $length, $this->apitoken);
    $total_audios = $videomodel->get_total_videos($searchValue, $this->apitoken);
    //var_dump($users); die;
    $dat = array();

    $count = $start + 1;
    foreach ($audios as $r) {
      $vid = "";
      if ($r->video_type == "mp4_video") {
        $vid = '<video  controls preload="none" width="300" height="200">
              <source src="' . $r->source . '" type="video/mp4">
            Your browser does not support the audio element.
            </video >';
      } else if ($r->video_type == "dailymotion_video") {
        $vid = '<iframe frameborder="0" width="300" height="200" src="//www.dailymotion.com/embed/video/' . $r->source . '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
      } else if ($r->video_type == "vimeo_video") {
        $vid = '<iframe frameborder="0" width="300" height="200" src="//player.vimeo.com/video/' . $r->source . '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
      } else if ($r->video_type == "youtube_video") {
        $vid = '<iframe frameborder="0" width="300" height="200" src="//www.youtube.com/embed/' . $r->source . '" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
      } else if ($r->video_type == "mpd_video") {
        $vid = '<video data-dashjs-player width="300" height="200" src="' . $r->source . '" controls></video>';
      } else if ($r->video_type == "m3u8_video") {
        $vid = "<video-js class='video-js vjs-default-skin' controls preload='auto' width='300' height='200'
                    data-setup='{}'>
                      <source src='" . $r->source . "' type='application/x-mpegURL'>
                    </video-js>";
      }
      $dat[] = array(
        $count,
        $vid,
        $r->title,
        $r->description,
        '
                <div class="dropdown">
                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="' . base_url() . '/editVideo/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
                    <a data-type="video" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
                    <i data-type="video" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
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

  public function newVideo()
  {
    $branchesmodel = new branchesmodel();
    $this->viewdata['branches'] = $branchesmodel->branchesListing($this->apitoken);
    return $this->view("media/newvideo", $this->viewdata);
  }

  public function editVideo($id = 0)
  {
    $videomodel = new videomodel();
    $this->viewdata['video'] = $videomodel->getVideoInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['video']) == 0) {
      return redirect()->to(base_url() . '/videos');
    }
    $branchesmodel = new branchesmodel();
    $this->viewdata['branches'] = $branchesmodel->branchesListing($this->apitoken);
    $_duration = new Duration;
    $this->viewdata['video']->duration = $_duration->formatted(($this->viewdata['video']->duration) / 1000);
    return $this->view("media/editvideo", $this->viewdata);
  }

  public function saveNewVideo()
  {
    $videomodel = new videomodel();
    $data = $this->get_data();
    if (isset($data) && isset($data->title)) {
      //var_dump($data); die;
      $branch = 0;
      if (isset($data->branch)) {
        $branch = $data->branch;
      }
      $media_type = "mp4_video";
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
      if (isset($data->duration) && !empty($data->duration)) {
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

      // Calculate duration in milliseconds, or 0 if not provided
      $duration_ms = 0;
      if (!empty($duration)) {
        $_duration = new Duration;
        $duration_ms = $_duration->toSeconds($duration) * 1000;
      }

      $info = array(
        'branch' => 1,
        'apitoken' => $this->apitoken,
        'category' => $category,
        'title' => $title,
        'description' => $description,
        'is_free' => $is_free,
        'can_download' => $can_download,
        'can_preview' => $can_preview,
        'preview_duration' => $preview_duration,
        'sub_category' => $subcategory,
        'duration' => $duration_ms,
        'video_type' => $media_type,
        'type' => 'video'
      );

      if ($media_type == "mp4_video") {
        //upload image file
        $video_upload = $this->upload_video();
        $thumb_upload = $this->upload_thumbnail();
        //var_dump($video_upload); die;
        //upload video file


        //if there are any error, display to user
        if ($video_upload[0] == 'error' || $thumb_upload[0] == 'error') {
          $msg = $video_upload[0] == 'error' ? "Audio upload error: " . $video_upload[1]['video'] : "";
          $msg .= $thumb_upload[0] == 'error' ? "\nThumbnail upload error: " . $thumb_upload[1]['thumbnail'] : "";
          echo json_encode(array("status" => "error", "msg" => $msg));
          exit;
        }
        $info['cover_photo'] = $thumb_upload[1];
        $info['source'] = $video_upload[1];
      } else if ($media_type == "youtube_video") {
        $info['cover_photo'] = isset($data->thumbnail_link) ? $data->thumbnail_link : '';
        $videoId = $this->getYoutubeId($data->media_link);
        $info['source'] = $videoId;

        // Check embeddability and cache result (YouTube Data API) — only if we have an ID
        if ($videoId != '') {
          $ytService = new \App\Libraries\YouTubeService();
          $ytModel = new \App\Models\YouTube_model();
          $check = $ytService->checkVideo($videoId);
          $ytModel->setCheck($videoId, $this->apitoken, $check['is_embeddable'], $check['reason'], $check['privacy_status'], $check['content_details']);
        }
      } else {
        $info['cover_photo'] = isset($data->thumbnail_link) ? $data->thumbnail_link : '';
        $info['source'] = $data->media_link;
      }

      $videomodel->addNewVideo($info);
    }
    echo json_encode(array("status" => $videomodel->status, "msg" => $videomodel->message));
    exit;
  }

  private function getYoutubeId($url)
  {
    if (str_contains($url, 'http')) {
      $parts = parse_url($url);
      // standard URL: look for v param
      if (isset($parts['query'])) {
        parse_str($parts['query'], $my_array);
        if (isset($my_array['v'])) return $my_array['v'];
      }
      // short youtu.be links or embed paths
      if (isset($parts['host']) && strpos($parts['host'], 'youtu.be') !== false) {
        return ltrim($parts['path'], '/');
      }
      if (isset($parts['path'])) {
        // /embed/VIDEOID
        $m = array_filter(explode('/', $parts['path']));
        if (count($m) > 0) {
          $last = array_values($m)[count($m) - 1];
          return $last;
        }
      }
      return '';
    } else {
      return $url;
    }
  }

  public function editVideoData()
  {
    $videomodel = new videomodel();
    $data = $this->get_data();
    if (!isset($data) || !isset($data->title)) {
      echo json_encode(array("status" => $videomodel->status, "msg" => $videomodel->message));
      exit;
    }

    $id = isset($data->id) ? $data->id : 0;
    $title = $data->title;
    $description = "";
    if (isset($data->description)) {
      $description = $data->description;
    }
    $branch = 0;
    if (isset($data->branch)) {
      $branch = $data->branch;
    }

    $duration = 0;
    if (isset($data->duration) && !empty($data->duration)) {
      $duration = $data->duration;
    }

    // Calculate duration in milliseconds, or 0 if not provided
    $duration_ms = 0;
    if (!empty($duration)) {
      $_duration = new Duration;
      $duration_ms = $_duration->toSeconds($duration) * 1000;
    }

    $info = array(
      'branch' => 1,
      'title' => $title,
      'description' => $description,
      'duration' => $duration_ms,
    );

    $videomodel->editVideoData($info, $id, $this->apitoken);

    echo json_encode(array("status" => $videomodel->status, "msg" => $videomodel->message));
    exit;
  }

  function deleteVideo($id = 0)
  {
    $videomodel = new videomodel();
    $video = $videomodel->getVideoInfo($id, $this->apitoken);
    if (count((array)$video) > 0) {
      @unlink('./uploads/videos/' . $this->apitoken . "/" . $video->source);
      @unlink('./uploads/thumbnails/' . $this->apitoken . "/" . $video->cover_photo);
    }
    $videomodel->deleteVideo($id, $this->apitoken);
    if ($videomodel->status == "ok") {
      $this->session->setFlashdata('success', $videomodel->message);
    } else {
      $this->session->setFlashdata('error', $videomodel->message);
    }
    return redirect()->to(base_url() . '/videos');
  }

  public function upload_video()
  {
    if (!file_exists('./uploads/videos/' . $this->apitoken)) {
      mkdir('./uploads/videos/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'video' => [
        'uploaded[video]',
        'mime_in[video,mp4,video/mp4,video/mp,video/webm]',
        'max_size[video,100000]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      //var_dump($data);
      return ['error', $this->validator->getErrors()];
    } else {

      $img = $this->request->getFile('video');
      $img->move('./uploads/videos/' . $this->apitoken);
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
    if (!file_exists('./uploads/thumbnails/' . $this->apitoken)) {
      mkdir('./uploads/thumbnails/' . $this->apitoken, 0777, true);
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
    $img->move('./uploads/thumbnails/' . $this->apitoken);
    return ['ok', $img->getName()];
  }
}
