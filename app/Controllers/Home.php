<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\Articles_model as articlesmodel;
use App\Models\Audio_model as audiomodel;
use App\Models\Books_model as booksmodel;
use App\Models\Hymns_model as hymnsmodel;
use App\Models\Members_model as membersmodel;
use App\Models\Prayer_model as prayermodel;
use App\Models\Testimony_model as testimonymodel;
use App\Models\Devotionals_model as devotionalsmodel;
use App\Models\Groups_model as groupsmodel;
use App\Models\Video_model as videomodel;
use App\Models\Donations_model as donationsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Manage_model as managemodel;

//use App\Models\Home_model as homemodel;

class Home extends BaseController
{
  protected $role = 0;
  protected $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $session = session();
    $this->apitoken = $session->get('apitoken');
    $this->role = $session->get('role');
  }

  public function index()
  {
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings($this->apitoken);
    if ($settings) {
      $this->viewdata['churchname'] = $settings->churchname;
      $this->viewdata['currencycode'] = $settings->currency_code;
    } else {
      $this->viewdata['churchname'] = "";
      $this->viewdata['currencycode'] = "USD";
    }
    //branches
    $branchesmodel = new branchesmodel();
    $this->viewdata['branches'] = $branchesmodel->getTotalItems($this->apitoken);
    //audios
    $audiomodel = new audiomodel();
    $this->viewdata['audios'] = $audiomodel->getTotalItems($this->apitoken);
    //videos
    $videomodel = new videomodel();
    $this->viewdata['videos'] = $videomodel->getTotalItems($this->apitoken);
    //prayer
    //$prayermodel = new prayermodel();
    //$this->viewdata['prayers'] = $prayermodel->getTotalItems();
    //testimonies
    //$testimonymodel = new testimonymodel();
    //$this->viewdata['testimonies'] = $testimonymodel->getTotalItems();
    //devotionals
    //$devotionalsmodel = new devotionalsmodel();
    //$this->viewdata['devotionals'] = $devotionalsmodel->getTotalItems();
    //hymns
    //$hymnsmodel = new hymnsmodel();
    //$this->viewdata['hymns'] = $hymnsmodel->getTotalItems();
    //members
    $membersmodel = new membersmodel();
    $this->viewdata['members'] = $membersmodel->getTotalItems($this->apitoken);
    //groups
    $groupsmodel = new groupsmodel();
    $this->viewdata['groups'] = $groupsmodel->getTotalItems($this->apitoken);
    //donations
    $firstdayoftheweek = date('Y-m-d', strtotime("this week")); //Monday
    $today = date('Y-m-d');
    $thismonth = date('m');
    $thisyear = date('Y');
    $donationsmodel = new donationsmodel();
    $this->viewdata['donations'] = $donationsmodel->getTotalItems($this->apitoken);
    $this->viewdata['donationsthisweek'] = $donationsmodel->getThisWeekDonationsAmount($firstdayoftheweek, $today, $this->apitoken);
    $this->viewdata['donationsthismonth'] = $donationsmodel->getDonationsAmount($thismonth, $thisyear, $this->apitoken);
    $this->viewdata['donationsthisyear'] = $donationsmodel->getDonationsAmount(0, $thisyear, $this->apitoken);
    $this->viewdata['alldonations'] = $donationsmodel->getDonationsAmount(0, 0, $this->apitoken);
    $this->viewdata['recentdonations'] = $donationsmodel->getRecentDonations($this->apitoken);

    return $this->view("home", $this->viewdata);
  }
}
