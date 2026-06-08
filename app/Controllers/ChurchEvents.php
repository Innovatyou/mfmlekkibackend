<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Events_model as eventsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;
//use App\Models\Home_model as homemodel;

class ChurchEvents extends BaseController
{
  protected $session;
  protected $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->apitoken = $this->session->get('apitoken');

    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    $eventsmodel = new eventsmodel();
    $this->viewdata['events'] = $eventsmodel->eventsListing($this->apitoken);
    return $this->view("events/listing", $this->viewdata);
  }

  public function newEvent()
  {
    return $this->view("events/new", $this->viewdata);
  }

  public function editEvent($id = 0)
  {
    $eventsmodel = new eventsmodel();
    $this->viewdata['event'] = $eventsmodel->getEventInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['event']) == 0) {
      return redirect()->to(base_url() . '/events');
    }
    $this->viewdata['event']->time = str_replace("AM", "", $this->viewdata['event']->time);
    $this->viewdata['event']->time = str_replace("PM", "", $this->viewdata['event']->time);
    $this->viewdata['event']->time = trim($this->viewdata['event']->time);
    return $this->view("events/edit", $this->viewdata);
  }

  function savenewevent()
  {
    $eventsmodel = new eventsmodel();
    $upload = $this->upload_thumbnail();
    if ($upload[0] == 'ok') {
      $title = $this->request->getVar('title');
      $details = $this->request->getVar('details');
      $date = $this->request->getVar('date');
      $time = $this->request->getVar('time');
      $mer = intval($time) < 12 ? 'AM' : 'PM';
      $time = $time . " " . $mer;

      $_date = \DateTime::createFromFormat("Y-m-d", $date);
      $year =  $_date->format("Y") + 0;
      $month =  $_date->format("m") + 0;
      $day =  $_date->format("d") + 0;
      $info = array(
        'apitoken' => $this->apitoken,
        'title' => $title,
        'details' => $details,
        'date' => $date,
        'year' => $year,
        'month' => $month,
        'day' => $day,
        'time' => $time,
        'thumbnail' => $upload[1]
      );
      //var_dump($info); die;

      $insertid = $eventsmodel->addNewEvent($info);
      if ($insertid != 0) {
        $itm = $eventsmodel->getEventInfo($insertid, $this->apitoken);
        //var_dump($article); die;
        if (count((array)$itm) > 0) {
          $settingsmodel = new settingsmodel();
          $server_key = $settingsmodel->getFcmServerKey($this->apitoken);
          $fcmmodel = new fcmmodel();
          $fcmmodel->push_item_data($server_key, $itm, "Event", $this->apitoken);
        }
      }
      if ($eventsmodel->status == "ok") {
        $this->session->setFlashdata('success', $eventsmodel->message);
      } else {
        $this->session->setFlashdata('error', $eventsmodel->message);
      }
    } else {
      $this->session->setFlashdata('error', $upload[1]);
    }
    return redirect()->to(base_url() . '/newEvent');
  }


  function editEventData()
  {
    $eventsmodel = new eventsmodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $details = $this->request->getVar('details');
    $date = $this->request->getVar('date');
    $time = $this->request->getVar('time');
    $mer = intval($time) < 12 ? 'AM' : 'PM';
    $time = $time . " " . $mer;

    $_date = \DateTime::createFromFormat("Y-m-d", $date);
    $year =  $_date->format("Y") + 0;
    $month =  $_date->format("m") + 0;
    $day =  $_date->format("d") + 0;
    $info = array(
      'title' => $title,
      'details' => $details,
      'date' => $date,
      'year' => $year,
      'month' => $month,
      'day' => $day,
      'time' => $time,
    );
    //var_dump($info); die;

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();

      if ($upload[0] == 'ok') {
        $info['thumbnail'] = $upload[1];
      } else {
        $this->session->setFlashdata('error', $upload[1]);
        return redirect()->to(base_url() . '/editEvent/' . $id);
        return;
      }
    }

    $eventsmodel->editEvent($info, $id, $this->apitoken);
    if ($eventsmodel->status == "ok") {
      $this->session->setFlashdata('success', $eventsmodel->message);
    } else {
      $this->session->setFlashdata('error', $eventsmodel->message);
    }

    return redirect()->to(base_url() . '/editEvent/' . $id);
  }


  function deleteEvent($id = 0)
  {
    $eventsmodel = new eventsmodel();
    $eventsmodel->deleteEvent($id, $this->apitoken);
    if ($eventsmodel->status == "ok") {
      $this->session->setFlashdata('success', $eventsmodel->message);
    } else {
      $this->session->setFlashdata('error', $eventsmodel->message);
    }
    return redirect()->to(base_url() . '/eventsListing');
  }


  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/events/' . $this->apitoken)) {
      mkdir('./uploads/thumbnails/events/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'thumbnail' => [
        'uploaded[thumbnail]',
        'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        'max_size[thumbnail,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('thumbnail');
      $img->move('./uploads/thumbnails/events/' . $this->apitoken);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
