<?php

namespace App\Models;

use App\Models\Basemodel;

class Media_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  public function searchListing($query, $offset, $email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where("(`title` LIKE '%$query%'");
    $builder->orwhere("`description` LIKE '%$query%')");
    $builder->orderby('dateInserted', 'desc');
    $builder->limit(30, $offset);

    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      // ✅ CRITICAL: Ensure source (video ID/link) is ALWAYS a string, never int
      $res->source = isset($res->source) ? (string) $res->source : '';
      $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
      $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->comments_count = 0; //$this->get_total_comments($res->id);
      $res->user_liked = 0; //$this->checkIfUserLikedMedia($res->id,$email);
      // Provide normalized YouTube payload for YouTube media (no iframe/embed HTML)
      if (isset($res->video_type) && $res->video_type === 'youtube_video') {
        $res->normalized_video = $this->get_youtube_payload($res->source);
      }
    }
    return $result;
  }

  public function update_media_total_views($media)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->set('views_count', 'views_count+1', FALSE);
    $builder->where('id', $media);
    $builder->update();
  }


  function getLatestMedia()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->orderby('dateInserted', 'desc');
    $builder->limit(10);
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      // ✅ CRITICAL: Ensure source (video ID/link) is ALWAYS a string, never int
      $res->source = isset($res->source) ? (string) $res->source : '';
      $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
      $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->comments_count = 0; //$this->get_total_comments($res->id);
      $res->user_liked = 0; //$this->checkIfUserLikedMedia($res->id,$email);
      if (isset($res->video_type) && $res->video_type === 'youtube_video') {
        $res->normalized_video = $this->get_youtube_payload($res->source);
      }
    }
    return $result;
  }

  public function fetch_media($type, $page = 0, $email = "null")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('type', $type);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      // ✅ CRITICAL: Ensure source (video ID/link) is ALWAYS a string, never int
      $res->source = isset($res->source) ? (string) $res->source : '';
      $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
      $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
      $res->comments_count = 0; //$this->get_total_comments($res->id);
      $res->user_liked = 0; //$this->checkIfUserLikedMedia($res->id,$email);
      if (isset($res->video_type) && $res->video_type === 'youtube_video') {
        $res->normalized_video = $this->get_youtube_payload($res->source);
      }
    }
    return $result;
  }

  private function get_youtube_payload($video_id)
  {
    $ytModel = new YouTube_model();
    $check = $ytModel->getCheck($video_id);

    $is_embeddable = null;
    $reason = null;
    $privacy = null;
    if ($check) {
      $is_embeddable = isset($check->is_embeddable) ? (bool)$check->is_embeddable : null;
      $reason = isset($check->reason) ? $check->reason : null;
      $privacy = isset($check->privacy_status) ? $check->privacy_status : null;
    }

    // Construct normalized payload (do NOT include embed HTML)
    $payload = new \stdClass();
    $payload->video_type = 'youtube';
    $payload->video_id = $video_id;
    $payload->watch_url = 'https://www.youtube.com/watch?v=' . $video_id;
    // If check is not available (null) we treat as not embeddable and indicate the reason
    if ($check === null) {
      $payload->is_embeddable = false;
      $payload->reason_if_not_embeddable = 'check_unavailable';
    } else {
      $payload->is_embeddable = $is_embeddable === null ? false : $is_embeddable;
      if (!$payload->is_embeddable && $reason) {
        $payload->reason_if_not_embeddable = $reason;
      }
    }

    if ($privacy) $payload->privacy_status = $privacy;
    return $payload;
  }

  public function get_total_media($type)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select("COUNT(*) as num");
    $builder->where('type', $type);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  private function rewrite_old_url($url)
  {
    $url = trim((string)$url);
    $patterns = [
      'https://app.mfmlekkiphaseone.org/',
      'http://app.mfmlekkiphaseone.org/',
    ];
    foreach ($patterns as $old) {
      if (strpos($url, $old) === 0) {
        return rtrim($this->request_base_url(), '/') . '/' . ltrim(substr($url, strlen($old)), '/');
      }
    }
    return $url;
  }

  private function get_thumbnail_source($source)
  {
    $source = $this->rewrite_old_url($source);
    if ($this->isValidURL($source)) {
      return $source;
    }
    return $this->request_base_url() . "uploads/thumbnails/" . $source;
  }

  private function get_media_source($type, $video_type, $source)
  {
    $source = $this->rewrite_old_url($source);
    if ($this->isValidURL($source)) {
      return $source;
    }
    if ($type == "audio") {
      return $this->request_base_url() . "uploads/audios/" . $source;
    } else {
      if ($video_type == "mp4_video") {
        return $this->request_base_url() . "uploads/videos/" . $source;
      }
      return $source;
    }
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
