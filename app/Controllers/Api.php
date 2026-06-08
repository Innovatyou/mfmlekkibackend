  // Mark notifications as read
  public function markNotificationsRead()
  {
    $data = $this->get_data();
    $user_id = isset($data->user_id) ? filter_var($data->user_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $notification_ids = isset($data->notification_ids) ? $data->notification_ids : [];
    $notificationmodel = new notificationmodel();
    if (!empty($notification_ids) && is_array($notification_ids)) {
      foreach ($notification_ids as $nid) {
        $notificationmodel->markAsRead($nid, $user_id);
      }
    } else if ($user_id) {
      $notificationmodel->markAllAsRead($user_id);
    }
    echo json_encode(["status" => "ok"]);
    exit;
  }
<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Articles_model as articlesmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Account_model as accountmodel;
use App\Models\Verify_model as verifymodel;
use App\Models\Media_model as mediamodel;
use App\Models\Photos_model as photosmodel;
use App\Models\Radio_model as radiomodel;
use App\Models\Livestream_model as livestreammodel;
use App\Models\Books_model as booksmodel;
use App\Models\Groups_model as groupsmodel;
use App\Models\Prayer_model as prayermodel;
use App\Models\Testimony_model as testimonymodel;
use App\Models\Fcm_model as fcmmodel;
use App\Models\Events_model as eventsmodel;
use App\Models\Devotionals_model as devotionalsmodel;
use App\Models\Hymns_model as hymnsmodel;
use App\Models\Inbox_model as inboxmodel;
use App\Models\Notification_model as notificationmodel;
use App\Models\Chat_model as chatmodel;
use App\Models\Members_model as membersmodel;

class Api extends BaseController
{
  public $apitoken = "";
  public function __construct()
  {
    $this->apitoken = $this->check_headers();
  }

  //test notifications
  function testnotifications()
  {
    $fcmmodel = new fcmmodel();
    $fcmmodel->createNotificationMessage(["cMtRrpTuT1aR0PFwQuyWLV:APA91bH-SZP_ph9gOxzRejw-og7N9ICpgmHV1MLA8S5AZR9OhlnM_xIO4tF5Bmc_r99Mh1FLr2Va5rXD2cC_OKx-Mp7PqR0QUZZ-58DFBaJJ5amKuHrm3BUx3aGSEWcWg-jTVn8Q_fAY"]);
    exit;
  }


  //store user fcm token
  function storeFcmToken()
  {
    $data = $this->get_data();
    $fcmmodel = new fcmmodel();
    if (isset($data->token) && $data->token != "") {
      $token = $data->token;
      $version = "v2";
      $data = array("token" => $token, "app_version" => $version, "apitoken" => $this->apitoken);
      $fcmmodel->storeUserFcmToken($data);
    }
    echo json_encode(array(
      "status" => $fcmmodel->status,
      "msg" => $fcmmodel->message
    ));
    exit;
  }

  //get settings
  public function initapp()
  {
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getAppSettings($this->apitoken);
    //recommended videos/audios
    $eventsmodel = new eventsmodel();
    $upcoming_events = $eventsmodel->getUpcomingEvents($this->apitoken);
    //recommended videos/audios
    $mediamodel = new mediamodel();
    $latest_media = $mediamodel->getLatestMedia($this->apitoken);
    //latest articles
    $articlesmodel = new articlesmodel();
    $latest_articles = $articlesmodel->getLatestArticles($this->apitoken);
    //latest books
    $booksmodel = new booksmodel();
    $latest_books = $booksmodel->getLatestBooks($this->apitoken);
    //latest members
    $membersmodel = new membersmodel();
    $members = $membersmodel->getLatestMembers($this->apitoken, $email);
    echo json_encode(array(
      "status" => "ok",
      "latest_media" => $latest_media,
      "latest_articles" => $latest_articles,
      "latest_books" => $latest_books,
      "upcoming_events" => $upcoming_events,
      "settings" => $settings,
      "members" => $members,
      "statusCode" => 0
    ));
    exit;
  }

  public function getunseenmessages()
  {
    $data = $this->get_data();
    $user_id = isset($data->user_id) ? filter_var($data->user_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $notificationmodel = new notificationmodel();
    $notification_count = $notificationmodel->getUnreadCount($user_id);
    $chatmodel = new chatmodel();
    $unseen_chat_count = 0;
    if ($user_id != "") {
      $unseen_chat_count = $chatmodel->get_user_unseen_messages($user_id, $this->apitoken);
    }
    echo json_encode(array(
      "status" => "ok",
      "notification_count" => $notification_count,
      "unseen_chat_count" => $unseen_chat_count,
    ));
    exit;
  }

  function getitemdata()
  {
    $data = $this->get_data();
    //var_dump($data); die;
    $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $type = isset($data->type) ? filter_var($data->type, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    if ($type  == "") {
      echo json_encode(array("status" => "error"));
      exit;
    }

    if ($type == "Devotional") {
      $devotionalsmodel = new devotionalsmodel();
      $devotional = $devotionalsmodel->getDevotionalInfo($id, $this->apitoken);
      echo json_encode(array("status" => "ok", "devotional" => $devotional));
    }
    if ($type == "Event") {
      $eventsmodel = new eventsmodel();
      $events = $eventsmodel->getEventInfo($id, $this->apitoken);
      echo json_encode(array("status" => "ok", "events" => $events));
    }
    if ($type == "Article") {
      $articlesmodel = new articlesmodel();
      $article = $articlesmodel->getArticleInfo($id, $this->apitoken);
      echo json_encode(array("status" => "ok", "articles" => $article));
    }
    exit;
  }

  function getBibleVersions()
  {
    $booksmodel = new booksmodel();
    $versions = $booksmodel->biblesListing();
    echo json_encode(array("status" => "ok", "versions" => $versions));
    exit;
  }

  //fetch events
  function fetch_events()
  {
    $data = $this->get_data();
    $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("m");
    $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("Y");
    $eventsmodel = new eventsmodel();
    $results = $eventsmodel->fetchMonthsEvents($month, $year, $this->apitoken);
    echo json_encode(array("status" => "ok", "events" => $results));
    exit;
  }

  //fetch events
  function fetch_devotionals()
  {
    $data = $this->get_data();
    $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("m");
    $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("Y");
    $devotionalsmodel = new devotionalsmodel();
    $results = $devotionalsmodel->fetchMonthsDevotionals($month, $year, $this->apitoken);
    echo json_encode(array("status" => "ok", "devotionals" => $results));
    exit;
  }

  //fetch hymns
  function fetch_hymns()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $query = isset($data->query) ? filter_var($data->query, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $hymnsmodel = new hymnsmodel();
    $results = $hymnsmodel->hymnsListing($page, $query, $this->apitoken);
    $total_items = $hymnsmodel->get_total_hymns($query, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;

    echo json_encode(array("status" => "ok", "hymns" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch notifications (new unified endpoint)
  function fetch_notifications()
  {
    $data = $this->get_data();
    $user_id = isset($data->user_id) ? filter_var($data->user_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = isset($data->page) ? intval($data->page) : 0;
    $pageSize = isset($data->pageSize) ? intval($data->pageSize) : 20;
    $notificationmodel = new notificationmodel();
    $results = $notificationmodel->getUserNotifications($user_id, $page, $pageSize);
    $total_items = $notificationmodel->where('user_id', $user_id)->countAllResults();
    $isLastPage = (($page + 1) * $pageSize) >= $total_items;
    echo json_encode(array(
      "status" => "ok",
      "notifications" => $results,
      "inbox" => $results, // compatibility for mobile apps expecting 'inbox'
      "isLastPage" => $isLastPage
    ));
    exit;
  }

  //search audios/videos
  function search()
  {
    $data = $this->get_data();
    $result = [];
    if (isset($data->query)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "null";
      $query = $data->query;
      $offset = 0;
      if (isset($data->offset)) {
        $offset = $data->offset;
      }
      $mediamodel = new mediamodel();
      $result = $mediamodel->searchListing($query, $offset, $email, $this->apitoken);
    }
    echo json_encode(array("status" => "ok", "search" => $result));
    exit;
  }

  //fetch media
  function fetchmedia()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    if (isset($data->media_type)) {
      $type = $data->media_type;
      $page = 0;
      if (isset($data->page)) {
        $page = $data->page;
      }
      $mediamodel = new mediamodel();
      $results = $mediamodel->fetch_media($type, $page, "null", $this->apitoken);
      $total_items = $mediamodel->get_total_media($type, $this->apitoken);
      $isLastPage = (($page + 1) * 20) >= $total_items;
    }
    echo json_encode(array("status" => "ok", "media" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch media views
  function update_media_total_views()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    if (isset($data->media)) {
      $media = $data->media;
      $mediamodel = new mediamodel();
      $mediamodel->update_media_total_views($media, $this->apitoken);
    }
    echo json_encode(array("status" => "ok"));
    exit;
  }

  //fetch livestreams
  function fetchlivestreams()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $livestreammodel = new livestreammodel();
    $results = $livestreammodel->fetch_livestreams_app($page, $this->apitoken);
    
    // Sanitize output: ensure link/streamUrl is always a string, never int or null
    $results = array_map(function ($item) {
      $item->link = isset($item->link) 
                        ? (string) $item->link 
                        : '';
      return $item;
    }, $results);
    
    $total_items = $livestreammodel->get_total_livestreams($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "livestreams" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch radio
  function fetchradios()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $radiomodel = new radiomodel();
    $results = $radiomodel->fetch_radio($page, $this->apitoken);
    $total_items = $radiomodel->get_total_radio($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "radios" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch books
  function fetchbooks()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $booksmodel = new booksmodel();
    $results = $booksmodel->fetch_books($page, $this->apitoken);
    $total_items = $booksmodel->get_total_books($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "books" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch articles
  function fetcharticles()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $articlesmodel = new articlesmodel();
    $results = $articlesmodel->fetch_articles($this->apitoken, $page);
    $total_items = $articlesmodel->get_total_articles_app($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "articles" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch photos
  function fetchphotos()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $photosmodel = new photosmodel();
    $results = $photosmodel->fetch_photos($page, $this->apitoken);
    $total_items = $photosmodel->get_total_photos($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "photos" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  // Refresh cached YouTube embeddability checks for all YouTube videos for this apitoken
  public function refresh_youtube_checks()
  {
    $mediamodel = new \App\Models\Media_model();
    // fetch all youtube videos for this apitoken
    $db = \Config\Database::connect('default');
    $builder = $db->table('tbl_media');
    $builder->select('id, source');
    $builder->where('apitoken', $this->apitoken);
    $builder->where('video_type', 'youtube_video');
    $query = $builder->get();
    $videos = $query->getResult();

    $ytService = new \App\Libraries\YouTubeService();
    $ytModel = new \App\Models\YouTube_model();

    foreach ($videos as $v) {
      if (!isset($v->source) || trim($v->source) == '') continue;
      $check = $ytService->checkVideo($v->source);
      $ytModel->setCheck($v->source, $this->apitoken, $check['is_embeddable'], $check['reason'], $check['privacy_status'], $check['content_details']);
    }

    echo json_encode(['status' => 'ok', 'updated' => count($videos)]);
    exit;
  }

  //fetch prayers
  function fetchprayers()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = "";
    if (isset($data->email)) {
      $email = $data->email;
    }
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $prayermodel = new prayermodel();
    $results = $prayermodel->fetch_items($page, $this->apitoken, $email);
    $total_items = $prayermodel->get_total_items($this->apitoken, $email);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "prayers" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch groups
  function fetchgroups()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = "null";
    if (isset($data->email)) {
      $email = $data->email;
    }
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $groupsmodel = new groupsmodel();
    $results = $groupsmodel->fetch_items($email, $page, $this->apitoken);
    $total_items = $groupsmodel->get_total_items($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch my groups
  function fetchmygroups()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = "null";
    if (isset($data->email)) {
      $email = $data->email;
    }
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $groupsmodel = new groupsmodel();
    $results = $groupsmodel->fetchmygroups($email, $page, $this->apitoken);
    $total_items = $groupsmodel->get_my_total_groups($email, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch group activities
  function fetchgroupevents()
  {
    $data = $this->get_data();
    $groupid = 0;
    if (isset($data->groupid)) {
      $groupid = $data->groupid;
    }
    $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("m");
    $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("Y");
    $groupsmodel = new groupsmodel();
    $results = $groupsmodel->fetchMonthsEvents($groupid, $month, $year, $this->apitoken);
    echo json_encode(array("status" => "ok", "events" => $results));
    exit;
  }

  //join groups
  public function joingroup()
  {
    $email = $this->request->getVar('email');
    $groupid = $this->request->getVar('groupid');
    $settingsmodel = new settingsmodel();
    $status = $settingsmodel->getSettings($this->apitoken)->auto_approve_group_membership;
    $info = array(
      'groupid' => $groupid,
      'email' => $email,
      'status' => $status,
      "apitoken" => $this->apitoken
    );
    $groupsmodel = new groupsmodel();
    $groupsmodel->addNewGroupMember($info);
    echo json_encode(array("status" => "ok", "approved" => $status));
    exit;
  }

  //fetch testimonies
  function fetchtestimonies()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $testimonymodel = new testimonymodel();
    $results = $testimonymodel->fetch_items($page, $this->apitoken);
    $total_items = $testimonymodel->get_total_items($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "testimonies" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch testimonies
  function fetchbranches()
  {
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $branchesmodel = new branchesmodel();
    $results = $branchesmodel->fetch_items($page, $this->apitoken);
    $total_items = $branchesmodel->get_total_items($this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "branches" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  public function submitprayer()
  {
    $email = $this->request->getVar('email');
    $title = $this->request->getVar('title');
    $requester = $this->request->getVar('requester');
    $content = $this->request->getVar('content');
    $public = $this->request->getVar('public');
    $settingsmodel = new settingsmodel();
    $status = $settingsmodel->getSettings($this->apitoken)->auto_approve_prayer;
    $info = array(
      'email' => $email,
      'title' => $title,
      'branch' => 1,
      'content' => $content,
      'requester' => $requester,
      'status' => $status,
      'public' => $public,
      'apitoken' => $this->apitoken
    );
    $prayermodel = new prayermodel();
    $prayermodel->addNewItem($info);
    echo json_encode(array("status" => "ok", "approved" => $status));
    exit;
  }

  public function submittestimony()
  {
    $title = $this->request->getVar('title');
    $testifier = $this->request->getVar('testifier');
    $content = $this->request->getVar('content');
    $settingsmodel = new settingsmodel();
    $status = $settingsmodel->getSettings($this->apitoken)->auto_approve_testimony;
    $info = array(
      'title' => $title,
      'branch' => 1,
      'content' => $content,
      'testifier' => $testifier,
      'status' => $status,
      'apitoken' => $this->apitoken
    );
    $testimonymodel = new testimonymodel();
    $testimonymodel->addNewItem($info);
    echo json_encode(array("status" => "ok", "approved" => $status));
    exit;
  }

  //authentication functions
  public function loginapp()
  {
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $_email_error = $email != "" ? "" : "Email Address Is not valid!";
    $_password_error = $password == "" ? "Password is empty!" : "";
    if ($_email_error != "" || $_password_error != "") {
      echo json_encode(array("status" => "error", "message" => $_email_error . "\n" . $_password_error, "statuscode" => 0));
      exit;
    } else {
      $accountmodel = new accountmodel();
      $user = $accountmodel->authenticateUser($email, $password, $this->apitoken);
      if ($user && $user->verified == 1) {
        echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 1));
        exit;
      }
      //var_dump($accountmodel->status); die;
      echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 0));
      exit;
    }
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
      exit;
    }
  }

  //delete my account
  public function deletemyaccount()
  {
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($email == "") {
        echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        $accountmodel->deletemyaccount($email, $this->apitoken);
        //var_dump($accountmodel->status); die;
        echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "statuscode" => 0));
        exit;
      }
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
      exit;
    }
  }

  /**
   * This function used to register user
   */
  public function createaccount()
  {
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      $_email_error = $email != "" ? "" : "Email Address Is not valid!";
      $_password_error = $password == "" ? "Password is empty!" : "";
      if ($_email_error != "" || $_password_error != "") {
        echo json_encode(array("status" => "error", "message" => $_email_error . "\n" . $_password_error));
        exit;
      } else {
        $accountmodel = new accountmodel();
        $accountmodel->createAccount($email, $password, $this->apitoken);
        if ($accountmodel->status == "ok") {
          // Auto-verify: retrieve the newly created/updated user and return for immediate login
          $user = $accountmodel->authenticateUser($email, $password, $this->apitoken);
          if ($user && $user->verified == 1) {
            echo json_encode(array("status" => "ok", "message" => "Account created successfully. You are now logged in.", "user" => $user, "statuscode" => 1));
          } else {
            echo json_encode(array("status" => "ok", "message" => "Account created successfully.", "user" => $user, "statuscode" => 1));
          }
        } else {
          echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "statuscode" => 0));
        }
        exit;
      }
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found"));
      exit;
    }
  }

  /**
   * resend verification email to users email Address
   */
  public function resendVerificationMail()
  {
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($email == "") {
        echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        if ($accountmodel->verifyEmailExists($email, $this->apitoken) == TRUE) {
          $settingsmodel = new settingsmodel();
          $adminsettings = $settingsmodel->getSettings($this->apitoken);
          //send email
          $emailconfig = $settingsmodel->getEmailConfig();
          $branchname = $adminsettings->churchname;
          $link = $this->getVerificationLink($email, $this->apitoken);
          $subject = "Email Verification";
          $htmlContent = '<p>Thank you for registering on our platform.</p>';
          $htmlContent .= '<p>Please click on the link below to verify your email</p>';
          $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Verify Email", $htmlContent));
        }
        //var_dump($accountmodel->status); die;
        echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message));
        exit;
      }
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found"));
      exit;
    }
  }

  /**
   * This function used to send reset password link
   */
  public function resetPassword()
  {
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($email == "") {
        echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        if ($accountmodel->verifyEmailExists($email, $this->apitoken) == TRUE) {
          //if user email exists in the database
          //send password reset link
          $settingsmodel = new settingsmodel();
          $adminsettings = $settingsmodel->getSettings($this->apitoken);
          $emailconfig = $settingsmodel->getEmailConfig();
          $branchname = $adminsettings->churchname;
          $link = $this->getPasswordResetLink($email, $this->apitoken);
          $subject = "Password Reset";
          $htmlContent = '<p>Please click on the link below to reset your password</p>';
          $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Reset Password", $htmlContent));
        }
        echo json_encode(array("status" => "ok", "message" => "If the email exists in our platform, you should recieve an instruction on how to reset your password."));
        exit;
      }
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found"));
      exit;
    }
  }

  //verify email when user clicks on the link
  function verifyEmailLink($code)
  {
    $verifymodel = new verifymodel();
    // Check activation id in database
    $row = $verifymodel->checkActivationDetails($code);
    if ($row) {
      //delete activation details
      $verifymodel->deleteActivationDetails($code, $row->apitoken);
      //update user to verified
      $accountmodel = new accountmodel();
      $accountmodel->updateUserVerfication($row->email, $row->apitoken);
      //redirect to message page with message for user
      $data['title'] = 'Congratulations';
      $data['message'] = 'Your account has been successfully verified.';
      return view('success', $data); // this will load the view file
    } else {
      //redirect to message page with message for user
      $data['title'] = 'OOOPS!!!';
      $data['message'] = 'Your email address cannot be verified at the moment.';
      return view('failure', $data); // this will load the view file
    }
  }

  function resetLink($code)
  {
    $verifymodel = new verifymodel();
    // Check activation id in database
    $row = $verifymodel->checkActivationDetails($code, $this->apitoken);
    if ($row) {
      //redirect to message page with message for user
      $data['email'] = $row->email;
      $data['activation_id'] = $code;
      return view('resetPasswordForm', $data);
    } else {
      //redirect to message page with message for user
      $data['title'] = 'OOOPS!!!';
      $data['message'] = 'Password reset failed. Please try again some other time.';
      return view('failure', $data); // this will load the view file
    }
  }

  //change user password
  public function changeUserPassword()
  {
    $email = $this->request->getVar('email');
    $code = $this->request->getVar('activation_id');
    $password1 = $this->request->getVar('password1');
    $password2 = $this->request->getVar('password2');

    $session = session();
    if ($password1 != $password2) {
      $session->setFlashdata('error', "Passwords dont match");
      $data['email'] = $email;
      $data['activation_id'] = $code;
      return view('resetPasswordForm', $data);
    }

    $verifymodel = new verifymodel();
    $row = $verifymodel->checkActivationDetails($code, $this->apitoken);
    if (!$row) {
      //redirect to message page with message for user
      $data['title'] = 'OOOPS!!!';
      $data['message'] = 'Password reset failed. Please try again some other time.';
      return view('failure', $data); // this will load the view file
    }

    //
    $accountmodel = new accountmodel();
    $accountmodel->updateUserPassword($email, $password1, $this->apitoken);
    //delete activation details
    $verifymodel->deleteActivationDetails($code, $this->apitoken);
    $data['title'] = 'Congratulations';
    $data['message'] = 'Your password reset was successful. You can now login with your new password.';
    return view('success', $data);
  }

  public function updateUserProfile()
  {
    $email = $this->request->getVar('email');
    $firstname = $this->request->getVar('firstname');
    $lastname = $this->request->getVar('lastname');
    $dob = $this->request->getVar('dob');
    $phone = $this->request->getVar('phone');
    $gender = $this->request->getVar('gender');
    $address = $this->request->getVar('address');
    $occupation = $this->request->getVar('occupation');
    $aboutme = $this->request->getVar('aboutme');
    $facebook = $this->request->getVar('facebook');
    $twitter = $this->request->getVar('twitter');
    $linkedln = $this->request->getVar('linkedln');
    $info = array(
      'email' => $email,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'dob' => $dob,
      'age' => $this->getAge($dob),
      'phonenumber' => $phone,
      'gender' => $gender,
      'address' => $address,
      'occupation' => $occupation,
      'aboutme' => $aboutme,
      'facebook' => $facebook,
      'twitter' => $twitter,
      'linkedln' => $linkedln,
    );

    if (!empty($_FILES['avatar'])) {
      //var_dump($_FILES['avatar']);
      $upload = $this->upload_avatar();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      } else {
        echo json_encode(array("status" => "error", "msg" => $upload[1]['avatar']));
        exit;
      }
    }

    if (!empty($_FILES['cover_photo'])) {
      $upload = $this->upload_coverphoto();
      if ($upload[0] == 'ok') {
        $info['coverphoto'] =  $upload[1];
      } else {
        echo json_encode(array("status" => "error", "msg" => $upload[1]['cover_photo']));
        exit;
      }
    }

    $accountmodel = new accountmodel();
    $accountmodel->updateUserProfile($info, $email, $this->apitoken);
    $user = $accountmodel->getUpdatedUserProfile($email, $this->apitoken);
    echo json_encode(array("status" => "ok", "msg" => "Profile was updated successfully", "user" => $user));
    exit;
  }

  function getAge($dateofbirth)
  {
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dateofbirth), date_create($today));
    return $diff->format('%y');
  }

  function upload_avatar()
  {
    if (!file_exists('./uploads/members/' . $this->apitoken)) {
      mkdir('./uploads/members/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'avatar' => [
        'uploaded[avatar]',
        'mime_in[avatar,image/jpg,image/jpeg,image/png]',
        'max_size[avatar,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('avatar');
      $img->move('./uploads/members/' . $this->apitoken);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }

  function upload_coverphoto()
  {
    if (!file_exists('./uploads/members/' . $this->apitoken)) {
      mkdir('./uploads/members/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'cover_photo' => [
        'uploaded[cover_photo]',
        'mime_in[cover_photo,image/jpg,image/jpeg,image/png]',
        'max_size[cover_photo,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('cover_photo');
      $img->move('./uploads/members/' . $this->apitoken);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
