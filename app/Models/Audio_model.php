<?php

namespace App\Models;

use App\Models\Basemodel;

class Audio_model extends Basemodel
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
    $builder->where('type', 'audio');
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function audioListing($columnName, $columnSortOrder, $searchValue, $start, $length)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('type', 'audio');

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
      $res->source = $this->get_media_source($res->source);
    }
    return $result;
  }

  public function get_total_audios($searchValue = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');

    $builder->select("COUNT(*) as num");
    $builder->where('tbl_media.type', 'audio');

    if ($searchValue != "") {
      $builder->like('title', $searchValue);
      $builder->orlike('description', $searchValue);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function addNewAudio($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $info['dateInserted'] = date('Y-m-d H:i:s');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $info['title'] . $this->applocal['upload_success'];
    return $db->insertID();

    return 0;
  }

  function getAudioInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->select('tbl_media.*');
    $builder->where('tbl_media.id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->thumbnail = $this->get_thumbnail_source($row->cover_photo);
      $row->audio = $this->get_media_source($row->source);
    }
    return $row;
  }

  function editAudioData($info, $id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->update($info);

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['audio_edit'];

  }

  function deleteAudio($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_media');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['audio_delete'];

  }

  private function get_thumbnail_source($source)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return $this->request_base_url() . "/uploads/thumbnails/" . $source;
  }

  private function get_media_source($source)
  {
    if ($this->isValidURL($source)) {
      return $source;
    }
    return $this->request_base_url() . "/uploads/audios/" . $source;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
