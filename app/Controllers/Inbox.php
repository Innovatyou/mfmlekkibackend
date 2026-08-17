<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Inbox_model as inboxmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;

class Inbox extends BaseController
{
  protected $role = 0;

  public function __construct()
  {
    $session = session();
    $this->role = $session->get('role');
  }

  public function index()
  {
    $inboxmodel = new inboxmodel();
    $this->viewdata['messages'] = $inboxmodel->inboxListing();
    return $this->view("inbox/listing", $this->viewdata);
  }

  public function newInbox()
  {
    return $this->view("inbox/new", $this->viewdata);
  }

  public function editInbox($id = 0)
  {
    $inboxmodel = new inboxmodel();
    $this->viewdata['inbox'] = $inboxmodel->getInboxInfo($id);
    if (count((array)$this->viewdata['inbox']) == 0) {
      return redirect()->to(base_url() . '/inbox');
    }
    return $this->view("inbox/edit", $this->viewdata);
  }

  public function resendInbox($id = 0)
  {
    $inboxmodel = new inboxmodel();
    $this->viewdata['inbox'] = $inboxmodel->getInboxInfo($id);
    if (count((array)$this->viewdata['inbox']) == 0) {
      return redirect()->to(base_url() . '/inbox');
    }
    return $this->view("inbox/resend", $this->viewdata);
  }

  public function sendnewinbox()
  {
    $title = $this->request->getVar('title');
    $message = $this->request->getVar('message');
    $info = array('itm_id' => 0, 'title' => $title, 'message' => $message, 'type' => "inbox", 'user' => "", 'email' => "", 'timestamp' => time());
    $inboxmodel = new inboxmodel();
    $inbox_id = $inboxmodel->addNewInbox($info);

    if ($inbox_id != 0) {
      $inbox = $inboxmodel->getInboxInfo($inbox_id);
      //var_dump($article); die;
      if (count((array)$inbox) > 0) {
        $settingsmodel = new settingsmodel();
        $server_key = $settingsmodel->getFcmServerKey();
        $fcmmodel = new fcmmodel();
        $fcmmodel->push_inbox_data($server_key, $inbox);
      }
    }

    $session = session();
    $session->setFlashdata('success', "Message sent successfully.");
    return redirect()->to(base_url() . '/inbox');
  }

  function editInboxData()
  {
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $message = $this->request->getVar('message');
    $info = array(
      'title' => $title,
      'message' => $message,
    );
    $inboxmodel = new inboxmodel();
    $inboxmodel->editInbox($info, $id);
    $session = session();
    $session->setFlashdata('success', "Message updated successfully.");
    return redirect()->to(base_url() . '/editInbox/' . $id);
  }

  function deleteInbox($id = 0)
  {
    $inboxmodel = new inboxmodel();
    $inboxmodel->deleteInbox($id);
    $session = session();
    if ($inboxmodel->status == "ok") {
      $session->setFlashdata('success', $inboxmodel->message);
    } else {
      $session->setFlashdata('error', $inboxmodel->message);
    }
    return redirect()->to(base_url() . '/inbox');
  }
}
