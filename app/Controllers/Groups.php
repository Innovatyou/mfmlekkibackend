<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Groups_model as groupsmodel;

class Groups extends BaseController
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
    $groupsmodel = new groupsmodel();
    $this->viewdata['groups'] = $groupsmodel->groupsListing($this->apitoken);
    return $this->view("groups/listing", $this->viewdata);
  }

  public function newGroup()
  {
    return $this->view("groups/new", $this->viewdata);
  }

  public function editGroup($id = 0)
  {
    $groupsmodel = new groupsmodel();
    $this->viewdata['group'] = $groupsmodel->getGroupInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['group']) == 0) {
      return redirect()->to(base_url() . '/groups');
    }
    return $this->view("groups/edit", $this->viewdata);
  }

  function savenewgroup()
  {
    $groupsmodel = new groupsmodel();
    $title = $this->request->getVar('title');
    $leader = $this->request->getVar('leader');
    $description = $this->request->getVar('description');
    $location = $this->request->getVar('location');
    $time = $this->request->getVar('time');
    $info = array(
      'apitoken' => $this->apitoken,
      'leader' => $leader,
      'title' => $title,
      'description' => $description,
      'location' => $location,
      'time' => $time,
    );
    //var_dump($info); die;

    $groupid = $groupsmodel->addNewGroup($info);
    /*foreach ($members as $itm) {
        $info2 = array(
            'listid' => $listid,
            'email' => $itm,
        );
        $groupsmodel->addNewListMember($info2);
      }*/
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
      return redirect()->to(base_url() . '/addMemberstoGroup/' . $groupid);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
      return redirect()->to(base_url() . '/newGroup');
    }
  }


  function editGroupData()
  {
    $groupsmodel = new groupsmodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $leader = $this->request->getVar('leader');
    $description = $this->request->getVar('description');
    $location = $this->request->getVar('location');
    $time = $this->request->getVar('time');
    $info = array(
      'leader' => $leader,
      'title' => $title,
      'description' => $description,
      'location' => $location,
      'time' => $time,
    );
    $groupsmodel->editGroup($info, $id, $this->apitoken);
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }

    return redirect()->to(base_url() . '/editGroup/' . $id);
  }


  function deleteGroup($id = 0)
  {
    $groupsmodel = new groupsmodel();
    $groupsmodel->deleteGroupMembers($id, $this->apitoken);
    $groupsmodel->deleteGroup($id, $this->apitoken);
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }
    return redirect()->to(base_url() . '/groups');
  }

  function editGroupMemberStatus($id, $status)
  {
    $groupsmodel = new groupsmodel();
    $info = array(
      'status' => $status,
    );
    $groupsmodel->editMemberStatus($info, $id, $this->apitoken);
    $group = $groupsmodel->getGroupMemberInfo($id, $this->apitoken);
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }
    return redirect()->to(base_url() . '/viewGroupMembers/' . $group->groupid);
  }

  function removeFromGroup($id, $groupid)
  {
    $groupsmodel = new groupsmodel();
    $groupsmodel->removeFromGroup($id, $this->apitoken);
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }
    return redirect()->to(base_url() . '/viewGroupMembers/' . $groupid);
  }

  public function viewGroupMembers($groupid)
  {
    $groupsmodel = new groupsmodel();
      $this->viewdata['group'] = $groupsmodel->getGroupInfo($groupid, $this->apitoken);
    if (count((array)$this->viewdata['group']) == 0) {
      return redirect()->to(base_url() . '/groups');
    }
      $this->viewdata['members'] = $groupsmodel->groupsMembersListing($groupid, $this->apitoken);
    return $this->view("groups/members", $this->viewdata);
  }

  public function addMemberstoGroup($groupid)
  {
    $groupsmodel = new groupsmodel();
      $this->viewdata['group'] = $groupsmodel->getGroupInfo($groupid, $this->apitoken);
    if (count((array)$this->viewdata['group']) == 0) {
      return redirect()->to(base_url() . '/groups');
    }
      $this->viewdata['members'] = $groupsmodel->fetchMembersNotinGroup($this->viewdata['group'], $this->apitoken);
    //var_dump($data); die;
    return $this->view("groups/addmembers", $this->viewdata);
  }

  function savenewmembersgroup()
  {
    $groupsmodel = new groupsmodel();
    $groupid = $this->request->getVar('id');
    $members = $this->request->getVar('members');
    if ($members != NULL && $members != "" && count($members) > 0) {
      foreach ($members as $itm) {
        $info2 = array(
          'apitoken' => $this->apitoken,
          'groupid' => $groupid,
          'email' => $itm,
        );
        $groupsmodel->addNewGroupMember($info2);
      }
      $this->session->setFlashdata('success', "Members added to group");
    }

    return redirect()->to(base_url() . '/viewGroupMembers/' . $groupid);
  }


  //group activities
  public function groupEvents($groupid)
  {
    $groupsmodel = new groupsmodel();
      $this->viewdata['group'] = $groupsmodel->getGroupInfo($groupid, $this->apitoken);
    if (count((array)$this->viewdata['group']) == 0) {
      return redirect()->to(base_url() . '/groups');
    }
      $this->viewdata['events'] = $groupsmodel->groupEventsListing($groupid, $this->apitoken);
    //var_dump($data['members']); die;
      $this->viewdata['groupid'] = $groupid;
    return $this->view("groups/events/listing", $this->viewdata);
  }


  public function newEvent($groupid)
  {
    $groupsmodel = new groupsmodel();
    $this->viewdata['groupid'] = $groupid;
    return $this->view("groups/events/new", $this->viewdata);
  }

  public function editEvent($id = 0)
  {
    $groupsmodel = new groupsmodel();
      $this->viewdata['event'] = $groupsmodel->getEventInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['event']) == 0) {
      return redirect()->to(base_url() . '/events');
    }
      $this->viewdata['event']->time = str_replace("AM", "", $this->viewdata['event']->time);
      $this->viewdata['event']->time = str_replace("PM", "", $this->viewdata['event']->time);
      $this->viewdata['event']->time = trim($this->viewdata['event']->time);
    return $this->view("groups/events/edit", $this->viewdata);
  }

  function savenewevent()
  {
    $groupsmodel = new groupsmodel();
    $upload = $this->upload_thumbnail();
    if ($upload[0] == 'ok') {
      $groupid = $this->request->getVar('groupid');
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
        'groupid' => $groupid,
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

      $groupsmodel->addNewEvent($info);
      if ($groupsmodel->status == "ok") {
        $this->session->setFlashdata('success', $groupsmodel->message);
      } else {
        $this->session->setFlashdata('error', $groupsmodel->message);
      }
    } else {
      $this->session->setFlashdata('error', $upload[1]);
    }
    return redirect()->to(base_url() . '/newGroupEvent/' . $groupid);
  }


  function editEventData()
  {
    $groupsmodel = new groupsmodel();
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
        return redirect()->to(base_url() . '/editGroupEvent/' . $id);
        return;
      }
    }

    $groupsmodel->editEvent($info, $id, $this->apitoken);
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }

    return redirect()->to(base_url() . '/editGroupEvent/' . $id);
  }


  function deleteEvent($id = 0)
  {
    $groupsmodel = new groupsmodel();
    $event = $groupsmodel->getEventInfo($id, $this->apitoken);
    if (!$event) {
      return redirect()->to(base_url() . '/groups');
    }
    $groupid = $event->groupid;
    $groupsmodel->deleteEvent($id, $this->apitoken);
    //var_dump($events); die;
    if ($groupsmodel->status == "ok") {
      $this->session->setFlashdata('success', $groupsmodel->message);
    } else {
      $this->session->setFlashdata('error', $groupsmodel->message);
    }
    return redirect()->to(base_url() . '/groupEvents/' . $groupid);
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
