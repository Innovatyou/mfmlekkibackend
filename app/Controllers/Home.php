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
use App\Models\MemberCare_model as CareModel;
use App\Models\Counseling_model as CounselingModel;
use App\Models\Marketplace_model as MarketplaceModel;
use App\Models\Partnership_model as PartnershipModel;

//use App\Models\Home_model as homemodel;

class Home extends BaseController
{
  protected $role = 0;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $session = session();
    $this->role = $session->get('role');
  }

  public function index()
  {
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    if ($settings) {
      $this->viewdata['churchname'] = $settings->churchname;
      $this->viewdata['currencycode'] = $settings->currency_code;
    } else {
      $this->viewdata['churchname'] = "";
      $this->viewdata['currencycode'] = "USD";
    }
    //branches
    $branchesmodel = new branchesmodel();
    $this->viewdata['branches'] = $branchesmodel->getTotalItems();
    //audios
    $audiomodel = new audiomodel();
    $this->viewdata['audios'] = $audiomodel->getTotalItems();
    //videos
    $videomodel = new videomodel();
    $this->viewdata['videos'] = $videomodel->getTotalItems();
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
    $this->viewdata['members'] = $membersmodel->getTotalItems();
    //groups
    $groupsmodel = new groupsmodel();
    $this->viewdata['groups'] = $groupsmodel->getTotalItems();
    //donations
    $firstdayoftheweek = date('Y-m-d', strtotime("this week")); //Monday
    $today = date('Y-m-d');
    $thismonth = date('m');
    $thisyear = date('Y');
    $donationsmodel = new donationsmodel();
    $this->viewdata['donations'] = $donationsmodel->getTotalItems();
    $this->viewdata['donationsthisweek'] = $donationsmodel->getThisWeekDonationsAmount($firstdayoftheweek, $today);
    $this->viewdata['donationsthismonth'] = $donationsmodel->getDonationsAmount($thismonth, $thisyear);
    $this->viewdata['donationsthisyear'] = $donationsmodel->getDonationsAmount(0, $thisyear);
    $this->viewdata['alldonations'] = $donationsmodel->getDonationsAmount(0, 0);
    $this->viewdata['recentdonations'] = $donationsmodel->getRecentDonations();

    // Member Care Intelligence
    $care = new CareModel();
    $this->viewdata['care_stats']     = $care->getDashboardStats();
    $this->viewdata['care_birthdays'] = $care->getUpcomingBirthdays(7);
    $this->viewdata['care_needs']     = $care->getMembersNeedingCare(5);

    // Counseling & Case Tracker
    $counseling = new CounselingModel();
    $this->viewdata['counsel_stats']    = $counseling->getDashboardStats();
    $this->viewdata['counsel_today']    = $counseling->getTodayReminders();
    $this->viewdata['counsel_upcoming'] = $counseling->getUpcomingReminders(7);

    // Church Marketplace
    $marketplace = new MarketplaceModel();
    $this->viewdata['market_stats']   = $marketplace->getDashboardStats();
    $this->viewdata['market_recent']  = $marketplace->getRecentListings(5);
    $this->viewdata['market_pending'] = $marketplace->getPendingItems(5);
    $mktSettings = $settings; // already loaded above
    $mktCurrencyMap = ['USD' => '$', 'GBP' => '£', 'NGN' => '₦'];
    $mktCode = $mktSettings->marketplace_currency ?? $this->viewdata['currencycode'] ?? 'USD';
    $this->viewdata['market_sym'] = $mktCurrencyMap[$mktCode] ?? '$';

    // Partnership
    $partnership = new PartnershipModel();
    $this->viewdata['partner_stats']  = $partnership->getDashboardStats();
    $this->viewdata['partner_recent'] = $partnership->getRecentPartnerships(5);
    $this->viewdata['partner_tiers']  = $partnership->getTierStats();

    return $this->view("home", $this->viewdata);
  }
}
