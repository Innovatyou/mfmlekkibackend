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

  public function getTotalItems()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select("COUNT(*) as num");
    $builder->where('type', 'video');
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function videoListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
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
      $res->source = $this->get_media_source($res->source, $res->video_type);
    }
    return $result;
  }

  public function get_total_videos($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select("COUNT(*) as num");
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

  function getVideoInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('tbl_media.id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->thumbnail = $this->get_thumbnail_source($row->cover_photo);
      $row->video = $this->get_media_source($row->source, $row->video_type);
      if (isset($row->video_type) && $row->video_type === 'youtube_video') {
        // Provide normalized payload for client
        $ytModel = new \App\Models\YouTube_model();
        $check = $ytModel->getCheck($row->source);
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

  function editVideoData($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->update($info);

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['video_data_edit'];
  }

  function deleteVideo($id)
  {
    $id = intval($id);
    if ($id <= 0) {
      $this->status = $this->applocal['error'];
      $this->message = 'Invalid video ID.';
      return;
    }

    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->delete();
    if ($db->affectedRows() > 0) {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['video_data_del'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = 'Video not found.';
    }
  }

  private function get_thumbnail_source($source)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return site_url() . "uploads/thumbnails/" . $source;
  }

  private function get_media_source($source, $video_type)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    if ($video_type == "mp4_video") {
      return site_url() . "uploads/videos/" . $source;
    }
    return $source;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
