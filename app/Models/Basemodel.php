<?php

namespace App\Models;

use App\Models\Settings_model as settingsmodel;
use CodeIgniter\Model;

class Basemodel extends Model
{
  public $applocal;
  public function __construct()
  {
    $session = session();
    if ($session->get('role') == 2) {
      $apitoken = $session->get('apitoken');
      $locale = $this->getChurchDefaultLanguage($apitoken);
      $path = "Language/{$locale}.php";
      $this->applocal = require APPPATH . $path;
    } else {
      $path = "Language/en.php";
      $this->applocal = require APPPATH . $path;
    }
  }

  function getChurchDefaultLanguage($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_churches');
    $builder->select('tbl_churches.language');
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return $row->language;
    }
    return 'en';
  }
}
