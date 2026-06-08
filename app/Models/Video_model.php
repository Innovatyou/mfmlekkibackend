<?php

namespace App\Models;

use App\Models\Basemodel;

class Video_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select("COUNT(*) as num");
    $builder->where('type', 'video');
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function videoListing($columnName, $columnSortOrder, $searchValue, $start, $length, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('type', 'video');
    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('description', $searchValue);
    }
    if ($columnName != "") {
      $builder->orderby($columnName, $columnSortOrder);
    }
    $builder->limit($length, $start);

    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      $res->source = $this->get_media_source($res->source, $res->video_type, $apitoken);
    }
    return $result;
  }

  public function get_total_videos($searchValue = "", $apitoken = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $builder->where('tbl_media.type', 'video');
    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('description', $searchValue);
    }

    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function addNewVideo($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $info['dateInserted'] = date('Y-m-d H:i:s');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $info['title'] . $this->applocal['upload_success'];
    return $db->insertID();
  }

  function getVideoInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('tbl_media.id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->thumbnail = $this->get_thumbnail_source($row->cover_photo, $apitoken);
      $row->video = $this->get_media_source($row->source, $row->video_type, $apitoken);
      if (isset($row->video_type) && $row->video_type === 'youtube_video') {
        // Provide normalized payload for client
        $ytModel = new \App\Models\YouTube_model();
        $check = $ytModel->getCheck($row->source, $apitoken);
        $row->normalized_video = new \stdClass();
        $row->normalized_video->video_type = 'youtube';
        $row->normalized_video->video_id = $row->source;
        $row->normalized_video->watch_url = 'https://www.youtube.com/watch?v=' . $row->source;
        $row->normalized_video->is_embeddable = $check ? (bool)$check->is_embeddable : false;
        if ($check && $check->reason) $row->normalized_video->reason_if_not_embeddable = $check->reason;
      }
    }
    return $row;
  }

  function editVideoData($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['video_data_edit'];
  }

  function deleteVideo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['video_data_del'];
  }

  private function get_thumbnail_source($source, $apitoken)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return site_url() . "uploads/thumbnails/" . $apitoken . "/" . $source;
  }

  private function get_media_source($source, $video_type, $apitoken)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    if ($video_type == "mp4_video") {
      return site_url() . "uploads/videos/" . $apitoken . "/" . $source;
    }
    return $source;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
