<?php

namespace App\Models;

use App\Models\Basemodel;

class LandingContent_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getContent()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_landing_content');
    $builder->select('tbl_landing_content.*');
    $query = $builder->get();
    $row = $query->getRow(0);

    if (!$row) {
      $builder = $db->table('tbl_landing_content');
      $builder->insert(['id' => 1]);
      $query = $db->table('tbl_landing_content')->select('tbl_landing_content.*')->get();
      $row = $query->getRow(0);
    }

    if ($row) {
      $row->hero_image = $row->hero_image != "" ? $this->request_base_url() . "uploads/landing/" . $row->hero_image : "";
      $row->about_image = $row->about_image != "" ? $this->request_base_url() . "uploads/landing/" . $row->about_image : "";
    }

    return $row;
  }

  function updateContent($info)
  {
    $db = \Config\Database::connect("default");
    $info['updated_at'] = date('Y-m-d H:i:s');
    $builder = $db->table('tbl_landing_content');
    $builder->where('id', 1);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Website content updated successfully.";
  }
}
