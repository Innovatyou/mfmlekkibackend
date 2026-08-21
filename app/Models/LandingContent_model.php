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
      $row->hero_image = $row->hero_image != "" ? $this->request_base_url() . "uploads/landing/" . rawurlencode($row->hero_image) : "";
      $row->about_image = $row->about_image != "" ? $this->request_base_url() . "uploads/landing/" . rawurlencode($row->about_image) : "";
      $row->seo_og_image = $row->seo_og_image != "" ? $this->request_base_url() . "uploads/landing/" . rawurlencode($row->seo_og_image) : "";
      $headerLogo = $row->header_logo ?? "";
      $faviconImage = $row->favicon_image ?? "";
      $row->header_logo = $headerLogo !== "" ? $this->request_base_url() . "uploads/landing/" . rawurlencode($headerLogo) : "";
      $row->favicon_image = $faviconImage !== "" ? $this->request_base_url() . "uploads/landing/" . rawurlencode($faviconImage) : "";
      $row->header_text = $row->header_text ?? "";
      $row->favicon_text = $row->favicon_text ?? "";
      $row->hero_text_color = $row->hero_text_color ?? "#ffffff";
      $row->hero_overlay_opacity = $row->hero_overlay_opacity ?? 25;
      $mapEmbed = $row->contact_map_embed ?? "";
      if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $mapEmbed, $matches)) {
        $row->contact_map_embed = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
      }
      $row->web_app_url = $row->web_app_url ?? "";
      if ($row->web_app_url === "") {
        $row->web_app_url = env('WEB_APP_URL') ?: base_url('web/');
      }
      $row->web_app_login_text = $row->web_app_login_text ?? "Member Login";
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
