<?php

namespace App\Models;

use App\Models\Basemodel;

class Livestream_model extends Basemodel
{
  public $status;
  public $message;

  // Force casting to ensure streamUrl is always returned as string
  protected $casts = [
    'link' => 'string',
  ];

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  /**
   * The single most-recent stream currently marked Live (status = 0), for
   * the "Join Us Live" section on the public website. Null when nothing
   * is live right now.
   */
  public function getCurrentLive()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('status', 0);
    $builder->orderBy('id', 'DESC');
    $builder->limit(1);
    $row = $builder->get()->getRow(0);
    if ($row) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $row->link ?? '');
    }
    return $row;
  }

  public function fetch_livestreams_app($page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('status', 0);
    $builder->orderby('id', 'desc');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $row) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $row->link ?? '');
    }
    return $result;
  }

  public function fetch_livestreams($page = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->orderBy('title', 'ASC');
    $query = $builder->get();
    $result = $query->getResult();
    return $result;
  }

  public function getLatestForLanding(int $limit = 6)
  {
    $db = \Config\Database::connect("default");
    $rows = $db->table('tbl_livestreams')
      ->orderBy('id', 'DESC')
      ->limit($limit)
      ->get()
      ->getResult();

    foreach ($rows as $row) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $row->link ?? '');
    }
    return $rows;
  }


  public function get_total_livestreams()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->where('status', 0);
    $query = $builder->select("COUNT(*) as num")->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  function livestreamsListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    return $query->getResult();
  }

  function checkLivestreamExists($title, $id = 0)
  {
    //echo $name . " and ". $group;
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select("title");
    $builder->where("title", $title);
    if ($id != 0) {
      $builder->where("id !=", $id);
    }
    $query = $builder->get();
    //var_dump($query->result()); die;
    return $query->getResult();
  }


  function addNewLivestream($info)
  {
    if (empty($this->checkLivestreamExists($info['title'], 0))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->insert($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['ok'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['livestream_exist'];
    }
  }


  function editLivestream($info, $id)
  {
    if (empty($this->checkLivestreamExists($info['title'], $id))) {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->where('id', $id);
      $builder->update($info);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['new_livestream_edit'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['livestream_exist'];
    }
  }


  function getLivestreamInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->select('tbl_livestreams.*');
    $builder->where('id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->cover_photo = $this->get_thumbnail_source($row->cover_photo, $row->link ?? '');
    }
    return $row;
  }

  function deleteLivestream($id)
  {
    $id = intval($id);
    if ($id <= 0) {
      $this->status = $this->applocal['error'];
      $this->message = 'Invalid livestream ID.';
      return;
    }

    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_livestreams');
    $builder->where('id', $id);
    $builder->delete();
    if ($db->affectedRows() > 0) {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['new_livestream_delete'];
    } else {
      $this->status = $this->applocal['error'];
      $this->message = 'Livestream not found.';
    }
  }

  private function get_thumbnail_source($source, $link = '')
  {
    if (empty($source)) {
      $youtubeId = $this->youtubeVideoId($link);
      return $youtubeId ? 'https://i.ytimg.com/vi/' . $youtubeId . '/hqdefault.jpg' : '';
    }
    if ($this->isValidURL($source)) {
      return $source;
    }

    $relativePath = ltrim(str_replace('\\', '/', trim((string) $source)), '/');
    $relativePath = preg_replace('~^(?:tmpm/)?uploads/thumbnails/~i', '', $relativePath);
    $thumbnailRoot = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'thumbnails';

    if ($this->thumbnailExists($thumbnailRoot, $relativePath)) {
      return $this->thumbnailUrl($relativePath);
    }

    $purchaseCode = trim((string) env('PURCHASE_CODE', ''));
    if ($purchaseCode !== '') {
      $tenantPath = $purchaseCode . '/' . basename($relativePath);
      if ($this->thumbnailExists($thumbnailRoot, $tenantPath)) {
        return $this->thumbnailUrl($tenantPath);
      }
    }

    return $this->thumbnailUrl($relativePath);
  }

  private function thumbnailExists(string $root, string $relativePath): bool
  {
    $fullPath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    return is_file($fullPath);
  }

  private function thumbnailUrl(string $relativePath): string
  {
    $encodedPath = implode('/', array_map('rawurlencode', explode('/', $relativePath)));
    return $this->request_base_url() . 'uploads/thumbnails/' . $encodedPath;
  }

  private function youtubeVideoId($url)
  {
    if (!is_string($url) || trim($url) === '') return null;
    $patterns = [
      '~youtu\.be/([A-Za-z0-9_-]{6,})~',
      '~youtube(?:-nocookie)?\.com/(?:watch\?.*?v=|embed/|shorts/|live/)([A-Za-z0-9_-]{6,})~',
    ];
    foreach ($patterns as $pattern) {
      if (preg_match($pattern, $url, $matches)) return $matches[1];
    }
    return null;
  }

  function isValidURL($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }
}
