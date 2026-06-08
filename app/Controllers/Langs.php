<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Langs_model as langsmodel;
//use App\Models\Home_model as homemodel;

class Langs extends BaseController
{
  protected $session;
  protected $langsmodel;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->langsmodel = new langsmodel();
  }

  public function index()
  {
    $data['langs'] = $this->langsmodel->itemsListing();
    return $this->managerview("langs/listing", $data);
  }

  public function newlang()
  {
    return $this->managerview("langs/new", []);
  }

  public function editlang($id = 0)
  {
    $data['lang'] = $this->langsmodel->getiteminfo($id);
    if (count((array)$data['lang']) == 0) {
      return redirect()->to(base_url() . '/langs');
    }
    return $this->managerview("langs/edit", $data);
  }

  function savenewlang()
  {
    $id = $this->request->getVar('id');
    $english = $this->request->getVar('english');
    $french = $this->request->getVar('french');
    $spanish = $this->request->getVar('spanish');
    $german = $this->request->getVar('german');
    $arabic = $this->request->getVar('arabic');
    $portugese = $this->request->getVar('portugese');
    $portugesebr = $this->request->getVar('portugesebr');
    $info = array(
      'id' => $id,
      'english' => $english,
      'french' => $french,
      'spanish' => $spanish,
      'german' => $german,
      'arabic' => $arabic,
      'portugese' => $portugese,
      'portugesebr' => $portugesebr
    );
    $this->langsmodel->addnewitem($info);
    if ($this->langsmodel->status == "ok") {
      $this->session->setFlashdata('success', $this->langsmodel->message);
    } else {
      $this->session->setFlashdata('error', $this->langsmodel->message);
    }
    //redirect('newBranch');
    return redirect()->to(base_url() . '/newlang');
  }


  function editlangdata()
  {
    $id = $this->request->getVar('id');
    $english = $this->request->getVar('english');
    $french = $this->request->getVar('french');
    $spanish = $this->request->getVar('spanish');
    $german = $this->request->getVar('german');
    $arabic = $this->request->getVar('arabic');
    $portugese = $this->request->getVar('portugese');
    $portugesebr = $this->request->getVar('portugesebr');
    $info = array(
      'english' => $english,
      'french' => $french,
      'spanish' => $spanish,
      'german' => $german,
      'arabic' => $arabic,
      'portugese' => $portugese,
      'portugesebr' => $portugesebr
    );

    $this->langsmodel->edititem($info, $id);
    if ($this->langsmodel->status == "ok") {
      $this->session->setFlashdata('success', $this->langsmodel->message);
    } else {
      $this->session->setFlashdata('error', $this->langsmodel->message);
    }
    return redirect()->to(base_url() . '/editlang/' . $id);
    //redirect('editBranch/'.$id);
  }
}
