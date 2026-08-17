<?php

namespace App\Models;

use App\Models\Settings_model as settingsmodel;
use CodeIgniter\Model;

class Basemodel extends Model
{
  public $applocal;
  public function __construct()
  {
    $path = "Language/en.php";
    $this->applocal = require APPPATH . $path;
  }

  /**
   * Build base URL from the actual incoming request host so that
   * mobile apps, browsers, and production servers all get a reachable URL.
   */
  protected function request_base_url(): string
  {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
    return rtrim($scheme . '://' . $host . $script, '/') . '/';
  }
}
