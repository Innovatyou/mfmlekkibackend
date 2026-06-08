<?php

namespace App\Models;

use App\Models\Basemodel;

class Events_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  function getUpcomingEvents($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->select('tbl_events.*');
    $builder->where('apitoken', $apitoken);
    $builder->where('date >=', date("Y-m-d"));
    $builder->limit(3);
    $builder->orderBy('id', 'ASC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->thumbnail = base_url() . "/uploads/thumbnails/events/" . $apitoken . "/" . $res->thumbnail;
    }
    return $result;
  }

  function fetchMonthsEvents($month, $year, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->where('apitoken', $apitoken);
    $builder->where('month', $month);
    $builder->where('year', $year);
    $builder->orderBy('date', 'desc');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->thumbnail = base_url() . "/uploads/thumbnails/events/" . $apitoken . "/" . $res->thumbnail;
    }
    return $result;
  }


  public function getTotalItems($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->select("COUNT(*) as num");
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }


  function eventsListing($apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->select('tbl_events.*');
    $builder->where('apitoken', $apitoken);
    $builder->orderBy('date', 'DESC');
    $query = $builder->get();
    $result =  $query->getResult();
    foreach ($result as $res) {
      $res->thumbnail = base_url() . "/uploads/thumbnails/events/" . $apitoken . "/" . $res->thumbnail;
    }
    return $result;
  }

  function addNewEvent($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['event_add_success'];
    return $db->insertID();
    
    
    return 0;
  }


  function editEvent($info, $id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['event_edit_success'];
    
    

  }


  function getEventInfo($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->select('tbl_events.*');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (count((array)$row) > 0) {
      $row->thumbnail = base_url() . "/uploads/thumbnails/events/" . $apitoken . "/" . $row->thumbnail;
    }
    return $row;
  }

  function deleteEvent($id, $apitoken)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_events');
    $builder->where('id', $id);
    $builder->where('apitoken', $apitoken);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['event_delete_success'];
    
    

  }
}
