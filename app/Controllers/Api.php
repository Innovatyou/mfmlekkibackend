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
  public function __construct()
  {
  }

  //store user fcm token
  function storeFcmToken()
  {
    $data = $this->get_data();
    $fcmmodel = new fcmmodel();
    if (isset($data->token) && $data->token != "") {
      $token = $data->token;
      $version = "v2";
      $data = array("token" => $token, "app_version" => $version);
      $fcmmodel->storeUserFcmToken($data);
    }
    header('Content-Type: application/json'); echo json_encode(array(
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
    $settings = $settingsmodel->getAppSettings();
    if ($settings === null || $settings === false) {
      header('Content-Type: application/json'); echo json_encode(array("errors" => true, "message" => "Church settings not found"));
      exit;
    }
    //recommended videos/audios
    $eventsmodel = new eventsmodel();
    $upcoming_events = $eventsmodel->getUpcomingEvents();
    //recommended videos/audios
    $mediamodel = new mediamodel();
    $latest_media = $mediamodel->getLatestMedia();
    //latest articles
    $articlesmodel = new articlesmodel();
    $latest_articles = $articlesmodel->getLatestArticles();
    //latest books
    $booksmodel = new booksmodel();
    $latest_books = $booksmodel->getLatestBooks();
    //latest members
    $membersmodel = new membersmodel();
    $members = $membersmodel->getLatestMembers($email);
    header('Content-Type: application/json'); echo json_encode(array(
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
    // Flutter sends 'email', not 'user_id'
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    // Fallback: some callers may still send user_id
    $user_id = isset($data->user_id) ? filter_var($data->user_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

    // Resolve user_id from email if not directly provided
    if ($user_id === "" && $email !== "") {
      $userRow = db_connect()->table('tbl_android_users')
        ->select('id')
        ->where('email', $email)
        ->get()->getRowObject();
      if ($userRow) {
        $user_id = (string)$userRow->id;
      }
    }

    $notificationmodel = new notificationmodel();
    $notification_count = $notificationmodel->getUnreadCount($user_id);
    $chatmodel = new chatmodel();
    $unseen_chat_count = 0;
    if ($email !== "") {
      $unseen_chat_count = $chatmodel->get_user_unseen_messages($email);
    }
    header('Content-Type: application/json'); echo json_encode(array(
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
      header('Content-Type: application/json'); echo json_encode(array("status" => "error"));
      exit;
    }

    if ($type == "Devotional") {
      $devotionalsmodel = new devotionalsmodel();
      $devotional = $devotionalsmodel->getDevotionalInfo($id);
      header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "devotional" => $devotional));
    }
    if ($type == "Event") {
      $eventsmodel = new eventsmodel();
      $events = $eventsmodel->getEventInfo($id);
      header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "events" => $events));
    }
    if ($type == "Article") {
      $articlesmodel = new articlesmodel();
      $article = $articlesmodel->getArticleInfo($id);
      header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "articles" => $article));
    }
    exit;
  }

  function getBibleVersions()
  {
    $booksmodel = new booksmodel();
    $versions = $booksmodel->biblesListing();
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "versions" => $versions));
    exit;
  }

  //fetch events
  function fetch_events()
  {
    $data = $this->get_data();
    $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("m");
    $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("Y");
    $eventsmodel = new eventsmodel();
    $results = $eventsmodel->fetchMonthsEvents($month, $year);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "events" => $results));
    exit;
  }

  //fetch events
  function fetch_devotionals()
  {
    $data = $this->get_data();
    $month = isset($data->month) ? filter_var($data->month, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("m");
    $year = isset($data->year) ? filter_var($data->year, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : date("Y");
    $devotionalsmodel = new devotionalsmodel();
    $results = $devotionalsmodel->fetchMonthsDevotionals($month, $year);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "devotionals" => $results));
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
    $results = $hymnsmodel->hymnsListing($page, $query);
    $total_items = $hymnsmodel->get_total_hymns($query);
    $isLastPage = (($page + 1) * 20) >= $total_items;

    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "hymns" => $results, "isLastPage" => $isLastPage));
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
    header('Content-Type: application/json'); echo json_encode(array(
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
      $result = $mediamodel->searchListing($query, $offset, $email);
    }
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "search" => $result));
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
      $results = $mediamodel->fetch_media($type, $page, "null");
      $total_items = $mediamodel->get_total_media($type);
      $isLastPage = (($page + 1) * 20) >= $total_items;
    }
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "media" => $results, "isLastPage" => $isLastPage));
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
      $mediamodel->update_media_total_views($media);
    }
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
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
    $results = $livestreammodel->fetch_livestreams_app($page);
    
    // Sanitize output: ensure link/streamUrl is always a string, never int or null
    $results = array_map(function ($item) {
      $item->link = isset($item->link) 
                        ? (string) $item->link 
                        : '';
      return $item;
    }, $results);
    
    $total_items = $livestreammodel->get_total_livestreams();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "livestreams" => $results, "isLastPage" => $isLastPage));
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
    $results = $radiomodel->fetch_radio($page);
    $total_items = $radiomodel->get_total_radio();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "radios" => $results, "isLastPage" => $isLastPage));
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
    $results = $booksmodel->fetch_books($page);
    $total_items = $booksmodel->get_total_books();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "books" => $results, "isLastPage" => $isLastPage));
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
    $results = $articlesmodel->fetch_articles($page);
    $total_items = $articlesmodel->get_total_articles_app();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "articles" => $results, "isLastPage" => $isLastPage));
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
    $results = $photosmodel->fetch_photos($page);
    $total_items = $photosmodel->get_total_photos();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "photos" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  // Refresh cached YouTube embeddability checks for all YouTube videos
  public function refresh_youtube_checks()
  {
    $mediamodel = new \App\Models\Media_model();
    // fetch all youtube videos
    $db = \Config\Database::connect('default');
    $builder = $db->table('tbl_media');
    $builder->select('id, source');
    $builder->where('video_type', 'youtube_video');
    $query = $builder->get();
    $videos = $query->getResult();

    $ytService = new \App\Libraries\YouTubeService();
    $ytModel = new \App\Models\YouTube_model();

    foreach ($videos as $v) {
      if (!isset($v->source) || trim($v->source) == '') continue;
      $check = $ytService->checkVideo($v->source);
      $ytModel->setCheck($v->source, $check['is_embeddable'], $check['reason'], $check['privacy_status'], $check['content_details']);
    }

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'updated' => count($videos)]);
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
    $results = $prayermodel->fetch_items($page, $email);
    $total_items = $prayermodel->get_total_items($email);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "prayers" => $results, "isLastPage" => $isLastPage));
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
    $results = $groupsmodel->fetch_items($email, $page);
    $total_items = $groupsmodel->get_total_items();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
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
    $results = $groupsmodel->fetchmygroups($email, $page);
    $total_items = $groupsmodel->get_my_total_groups($email);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "groups" => $results, "isLastPage" => $isLastPage));
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
    $results = $groupsmodel->fetchMonthsEvents($groupid, $month, $year);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "events" => $results));
    exit;
  }

  //join groups
  public function joingroup()
  {
    $email = $this->request->getVar('email');
    $groupid = $this->request->getVar('groupid');
    $settingsmodel = new settingsmodel();
    $status = $settingsmodel->getSettings()->auto_approve_group_membership;
    $info = array(
      'groupid' => $groupid,
      'email' => $email,
      'status' => $status,
    );
    $groupsmodel = new groupsmodel();
    $groupsmodel->addNewGroupMember($info);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "approved" => $status));
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
    $results = $testimonymodel->fetch_items($page);
    $total_items = $testimonymodel->get_total_items();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "testimonies" => $results, "isLastPage" => $isLastPage));
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
    $results = $branchesmodel->fetch_items($page);
    $total_items = $branchesmodel->get_total_items();
    $isLastPage = (($page + 1) * 20) >= $total_items;
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "branches" => $results, "isLastPage" => $isLastPage));
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
    $status = $settingsmodel->getSettings()->auto_approve_prayer;
    $info = array(
      'email' => $email,
      'title' => $title,
      'branch' => 1,
      'content' => $content,
      'requester' => $requester,
      'status' => $status,
      'public' => $public,
    );
    $prayermodel = new prayermodel();
    $prayermodel->addNewItem($info);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "approved" => $status));
    exit;
  }

  public function submittestimony()
  {
    $title = $this->request->getVar('title');
    $testifier = $this->request->getVar('testifier');
    $content = $this->request->getVar('content');
    $settingsmodel = new settingsmodel();
    $status = $settingsmodel->getSettings()->auto_approve_testimony;
    $info = array(
      'title' => $title,
      'branch' => 1,
      'content' => $content,
      'testifier' => $testifier,
      'status' => $status,
    );
    $testimonymodel = new testimonymodel();
    $testimonymodel->addNewItem($info);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "approved" => $status));
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
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => $_email_error . "\n" . $_password_error, "statuscode" => 0));
      exit;
    } else {
      $accountmodel = new accountmodel();
      $user = $accountmodel->authenticateUser($email, $password);
      // authenticateUser() returns the matched row as soon as the email exists,
      // even when the password is wrong — so success must be read from
      // $accountmodel->status (which only reaches 'ok' once the password has
      // verified AND the account is verified), not from $user->verified alone.
      if ($user && $accountmodel->status === $accountmodel->applocal['ok']) {
        header('Content-Type: application/json'); echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 1));
        exit;
      }
      //var_dump($accountmodel->status); die;
      header('Content-Type: application/json'); echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "user" => $user, "statuscode" => 0));
      exit;
    }
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
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
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        $accountmodel->deletemyaccount($email);
        //var_dump($accountmodel->status); die;
        header('Content-Type: application/json'); echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "statuscode" => 0));
        exit;
      }
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "No data found", "statuscode" => 0));
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
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => $_email_error . "\n" . $_password_error));
        exit;
      } else {
        $accountmodel = new accountmodel();
        $accountmodel->createAccount($email, $password);
        if ($accountmodel->status == "ok") {
          // Auto-verify: retrieve the newly created/updated user and return for immediate login
          $user = $accountmodel->authenticateUser($email, $password);
          if ($user && $user->verified == 1) {
            header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "message" => "Account created successfully. You are now logged in.", "user" => $user, "statuscode" => 1));
          } else {
            header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "message" => "Account created successfully.", "user" => $user, "statuscode" => 1));
          }
        } else {
          header('Content-Type: application/json'); echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message, "statuscode" => 0));
        }
        exit;
      }
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "No data found"));
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
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        if ($accountmodel->verifyEmailExists($email) == TRUE) {
          $settingsmodel = new settingsmodel();
          $adminsettings = $settingsmodel->getSettings();
          //send email
          $emailconfig = $settingsmodel->getEmailConfig();
          $branchname = $adminsettings->churchname;
          $link = $this->getVerificationLink($email);
          $subject = "Email Verification";
          $htmlContent = '<p>Thank you for registering on our platform.</p>';
          $htmlContent .= '<p>Please click on the link below to verify your email</p>';
          $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Verify Email", $htmlContent));
        }
        //var_dump($accountmodel->status); die;
        header('Content-Type: application/json'); echo json_encode(array("status" => $accountmodel->status, "message" => $accountmodel->message));
        exit;
      }
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "No data found"));
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
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "Email Address Is not valid!"));
        exit;
      } else {
        $accountmodel = new accountmodel();
        if ($accountmodel->verifyEmailExists($email) == TRUE) {
          //if user email exists in the database
          //send password reset link
          $settingsmodel = new settingsmodel();
          $adminsettings = $settingsmodel->getSettings();
          $emailconfig = $settingsmodel->getEmailConfig();
          $branchname = $adminsettings->churchname;
          $link = $this->getPasswordResetLink($email);
          $subject = "Password Reset";
          $htmlContent = '<p>Please click on the link below to reset your password</p>';
          $this->sendEmail($branchname, $emailconfig, $email, $subject, $this->getActivationEmailTemplate($link, "Reset Password", $htmlContent));
        }
        header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "message" => "If the email exists in our platform, you should recieve an instruction on how to reset your password."));
        exit;
      }
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "error", "message" => "No data found"));
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
      $verifymodel->deleteActivationDetails($code);
      //update user to verified
      $accountmodel = new accountmodel();
      $accountmodel->updateUserVerfication($row->email);
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
    $row = $verifymodel->checkActivationDetails($code);
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
    $row = $verifymodel->checkActivationDetails($code);
    if (!$row) {
      //redirect to message page with message for user
      $data['title'] = 'OOOPS!!!';
      $data['message'] = 'Password reset failed. Please try again some other time.';
      return view('failure', $data); // this will load the view file
    }

    //
    $accountmodel = new accountmodel();
    $accountmodel->updateUserPassword($email, $password1);
    //delete activation details
    $verifymodel->deleteActivationDetails($code);
    $data['title'] = 'Congratulations';
    $data['message'] = 'Your password reset was successful. You can now login with your new password.';
    return view('success', $data);
  }

  public function updateUserProfile()
  {
    // Read JSON body directly — bypasses getVar() which can interfere in some CI4 versions
    $fields = [];
    $json = $this->request->getJSON(true); // true = associative array
    if (is_array($json) && isset($json['data']) && is_array($json['data'])) {
      $fields = $json['data'];
    } elseif (is_array($json)) {
      $fields = $json;
    } else {
      // Fallback: read raw body manually
      $rawBody = file_get_contents('php://input');
      if (!empty($rawBody)) {
        $decoded = json_decode($rawBody, true);
        if (is_array($decoded) && isset($decoded['data'])) {
          $fields = $decoded['data'];
        }
      }
    }

    // Last resort: check multipart form-data
    if (empty($fields)) {
      $fields = $_POST;
    }

    $getField = fn($name) => $fields[$name] ?? null;

    $email = $getField('email');
    if (empty($email)) {
      $json2 = $this->request->getJSON(true);
      header('Content-Type: application/json');
      echo json_encode([
        "status" => "error",
        "msg" => "Could not read request data. Please try again.",
        "dbg_fields_keys" => array_keys($fields),
        "dbg_json_keys" => is_array($json2) ? array_keys($json2) : "not_array",
        "dbg_json_data_keys" => (is_array($json2) && isset($json2['data'])) ? array_keys($json2['data']) : "no_data_key",
      ]);
      exit;
    }
    $firstname = $getField('firstname');
    $lastname = $getField('lastname');
    $dob = $getField('dob');
    $phone = $getField('phone');
    $gender = $getField('gender');
    $address = $getField('address');
    $occupation = $getField('occupation');
    $aboutme = $getField('aboutme');
    $facebook = $getField('facebook');
    $twitter = $getField('twitter');
    $linkedln = $getField('linkedln');
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

    // Handle avatar: base64 (JSON mode) or multipart file upload
    $avatar_base64 = $getField('avatar_base64');
    if (!empty($avatar_base64)) {
      $ext = $getField('avatar_ext') ?? 'jpg';
      $ext = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']) ? strtolower($ext) : 'jpg';
      $imageData = base64_decode($avatar_base64, true);
      if ($imageData !== false) {
        if (!file_exists('./uploads/members/')) {
          mkdir('./uploads/members/', 0777, true);
        }
        $filename = uniqid('av_', true) . '.' . $ext;
        file_put_contents('./uploads/members/' . $filename, $imageData);
        $info['thumbnail'] = $filename;
      }
    } elseif (!empty($_FILES['avatar'])) {
      $upload = $this->upload_avatar();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] = $upload[1];
      } else {
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "msg" => $upload[1]['avatar']));
        exit;
      }
    }

    // Handle cover photo: base64 (JSON mode) or multipart file upload
    $cover_photo_base64 = $getField('cover_photo_base64');
    if (!empty($cover_photo_base64)) {
      $ext = $getField('cover_photo_ext') ?? 'jpg';
      $ext = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']) ? strtolower($ext) : 'jpg';
      $imageData = base64_decode($cover_photo_base64, true);
      if ($imageData !== false) {
        if (!file_exists('./uploads/members/')) {
          mkdir('./uploads/members/', 0777, true);
        }
        $filename = uniqid('cv_', true) . '.' . $ext;
        file_put_contents('./uploads/members/' . $filename, $imageData);
        $info['coverphoto'] = $filename;
      }
    } elseif (!empty($_FILES['cover_photo'])) {
      $upload = $this->upload_coverphoto();
      if ($upload[0] == 'ok') {
        $info['coverphoto'] = $upload[1];
      } else {
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "msg" => $upload[1]['cover_photo']));
        exit;
      }
    }

    $accountmodel = new accountmodel();
    $accountmodel->updateUserProfile($info, $email);
    $user = $accountmodel->getUpdatedUserProfile($email);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "msg" => "Profile was updated successfully", "user" => $user));
    exit;
  }

  function getAge($dateofbirth)
  {
    if (empty($dateofbirth)) return 0;
    $dt = date_create($dateofbirth);
    if (!$dt) return 0;
    return (int) date_diff($dt, date_create())->format('%y');
  }

  function upload_avatar()
  {
    if (!file_exists('./uploads/members/')) {
      mkdir('./uploads/members/', 0777, true);
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
      $img->move('./uploads/members/');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }

  function upload_coverphoto()
  {
    if (!file_exists('./uploads/members/')) {
      mkdir('./uploads/members/', 0777, true);
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
      $img->move('./uploads/members/');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }

  // ─────────────────────────────────────────────────────────────────
  // MARKETPLACE API ENDPOINTS
  // ─────────────────────────────────────────────────────────────────

  public function fetchMarketplaceCategories()
  {
    $model = new \App\Models\Marketplace_model();
    $categories = $model->getCategories();

    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    $code = $settings->marketplace_currency ?? 'USD';
    $symbols = ['USD' => '$', 'GBP' => '£', 'NGN' => '₦'];
    $currencySymbol = $symbols[$code] ?? '$';

    header('Content-Type: application/json'); echo json_encode([
      'status' => 'ok',
      'categories' => $categories,
      'currency_symbol' => $currencySymbol,
    ]);
    exit;
  }

  public function fetchMarketplaceListings()
  {
    $data = $this->get_data();
    $search = isset($data->search) ? filter_var($data->search, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $categoryId = (isset($data->category_id) && $data->category_id !== '' && $data->category_id !== null)
      ? (int)$data->category_id : null;
    $start = isset($data->start) ? max(0, (int)$data->start) : 0;

    $model = new \App\Models\Marketplace_model();
    $items = $model->getActiveItems($categoryId, $search, 20, $start);
    $total = $model->countActiveItems($categoryId, $search);

    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    $code = $settings->marketplace_currency ?? 'USD';
    $symbols = ['USD' => '$', 'GBP' => '£', 'NGN' => '₦'];
    $currencySymbol = $symbols[$code] ?? '$';

    header('Content-Type: application/json'); echo json_encode([
      'status' => 'ok',
      'items' => $items,
      'total' => (int)$total,
      'currency_symbol' => $currencySymbol,
    ]);
    exit;
  }

  public function fetchMyMarketplaceListings()
  {
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (empty($email)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $items = $model->getItemsByEmail($email);

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'items' => $items]);
    exit;
  }

  public function submitMarketplaceListing()
  {
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (empty($email)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    $title = isset($data->title) ? trim(filter_var($data->title, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    if (empty($title)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Title is required']);
      exit;
    }

    $sellerName = isset($data->seller_name) ? filter_var($data->seller_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    if (empty($sellerName)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Seller name is required']);
      exit;
    }

    $isFree = isset($data->is_free) && ($data->is_free === true || $data->is_free === 1 || $data->is_free === '1');
    $price = $isFree ? 0.00 : (float)($data->price ?? 0);
    $condition = isset($data->item_condition) ? filter_var($data->item_condition, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'used';

    $info = [
      'church_id'      => 0,
      'seller_name'    => $sellerName,
      'seller_email'   => filter_var($data->seller_email ?? $email, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
      'seller_phone'   => filter_var($data->seller_phone ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
      'category_id'    => isset($data->category_id) && $data->category_id ? (int)$data->category_id : null,
      'title'          => $title,
      'description'    => isset($data->description) ? filter_var($data->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'price'          => $price,
      'is_free'        => $isFree ? 1 : 0,
      'item_condition' => in_array($condition, ['new', 'used']) ? $condition : 'used',
      'location'       => isset($data->location) ? filter_var($data->location, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'status'         => 'pending',
      'views'          => 0,
      'is_featured'    => 0,
      'created_at'     => date('Y-m-d H:i:s'),
    ];

    $model = new \App\Models\Marketplace_model();
    $item_id = $model->addItem($info);

    header('Content-Type: application/json'); echo json_encode([
      'status'  => 'ok',
      'item_id' => $item_id,
      'message' => 'Listing submitted for review.',
    ]);
    exit;
  }

  public function uploadMarketplacePhoto()
  {
    $email   = isset($_POST['email'])   ? filter_var($_POST['email'],   FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;

    if (empty($email) || $item_id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $item  = $model->getItemInfo($item_id);

    if (!$item || $item->seller_email !== $email) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      exit;
    }

    $existingCount = $model->countPhotos($item_id);
    if ($existingCount >= 10) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Maximum 10 photos allowed']);
      exit;
    }

    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'No photo received']);
      exit;
    }

    $allowed  = ['image/jpeg', 'image/png', 'image/webp'];
    $mimeType = mime_content_type($_FILES['photo']['tmp_name']);
    if (!in_array($mimeType, $allowed)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Only image files are allowed (jpg, png, webp)']);
      exit;
    }

    $uploadDir = './uploads/marketplace/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $filename = 'mkt_' . uniqid() . '.' . ($ext ?: 'jpg');

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Failed to save photo']);
      exit;
    }

    $model->addPhotos($item_id, [$filename]);

    if ($existingCount === 0) {
      $model->editItem(['image' => $filename], $item_id);
    }

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'filename' => $filename]);
    exit;
  }

  public function deleteMyMarketplaceListing()
  {
    $data    = $this->get_data();
    $email   = isset($data->email)   ? filter_var($data->email,   FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $item_id = isset($data->item_id) ? (int)$data->item_id : 0;

    if (empty($email) || $item_id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $item  = $model->getItemInfo($item_id);

    if (!$item || $item->seller_email !== $email) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      exit;
    }

    if (!in_array($item->status, ['pending', 'inactive'])) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Only pending or rejected listings can be deleted']);
      exit;
    }

    $photos = $model->getItemPhotos($item_id);
    foreach ($photos as $p) {
      $path = './uploads/marketplace/' . $p->filename;
      if (file_exists($path)) unlink($path);
    }
    $model->deleteAllPhotos($item_id);
    $model->deleteItem($item_id);

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'message' => 'Listing deleted']);
    exit;
  }

  public function fetchMarketplaceItem()
  {
    $data    = $this->get_data();
    $item_id = isset($data->item_id) ? (int)$data->item_id : 0;

    if ($item_id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Invalid item ID']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $item  = $model->getItemInfo($item_id);

    if (!$item) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Item not found']);
      exit;
    }

    $model->incrementViews($item_id);

    $photos  = $model->getItemPhotos($item_id);
    $itemArr = (array) $item;
    $itemArr['photos'] = $photos;

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'item' => $itemArr]);
    exit;
  }

  public function submitMarketplaceInquiryApp()
  {
    $data = $this->get_data();

    $item_id = isset($data->item_id) ? (int)$data->item_id : 0;
    $name    = isset($data->name)    ? filter_var($data->name,    FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email   = isset($data->email)   ? filter_var($data->email,   FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $phone   = isset($data->phone)   ? filter_var($data->phone,   FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $message = isset($data->message) ? filter_var($data->message, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if ($item_id === 0 || empty($name) || empty($message)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Name, message and item are required']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $model->addInquiry([
      'item_id'    => $item_id,
      'name'       => $name,
      'email'      => $email,
      'phone'      => $phone,
      'message'    => $message,
      'created_at' => date('Y-m-d H:i:s'),
    ]);

    header('Content-Type: application/json'); echo json_encode(['status' => $model->status, 'message' => $model->message]);
    exit;
  }

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
    header('Content-Type: application/json'); echo json_encode(["status" => "ok"]);
    exit;
  }

  public function updateMarketplaceListing()
  {
    $data    = $this->get_data();
    $email   = isset($data->email)   ? filter_var($data->email,   FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $item_id = isset($data->item_id) ? (int)$data->item_id : 0;

    if (empty($email) || $item_id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
      exit;
    }

    $model = new \App\Models\Marketplace_model();
    $item  = $model->getItemInfo($item_id);

    if (!$item || $item->seller_email !== $email) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      exit;
    }

    $title = isset($data->title) ? trim(filter_var($data->title, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    if (empty($title)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Title is required']);
      exit;
    }

    $isFree    = isset($data->is_free) && ($data->is_free === true || $data->is_free === 1 || $data->is_free === '1');
    $price     = $isFree ? 0.00 : (float)($data->price ?? 0);
    $condition = isset($data->item_condition) ? filter_var($data->item_condition, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'used';

    $updates = [
      'title'          => $title,
      'description'    => isset($data->description) ? filter_var($data->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'category_id'    => isset($data->category_id) && $data->category_id ? (int)$data->category_id : null,
      'price'          => $price,
      'is_free'        => $isFree ? 1 : 0,
      'item_condition' => in_array($condition, ['new', 'used']) ? $condition : 'used',
      'location'       => isset($data->location) ? filter_var($data->location, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'seller_name'    => isset($data->seller_name) ? filter_var($data->seller_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'seller_phone'   => isset($data->seller_phone) ? filter_var($data->seller_phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '',
      'status'         => 'pending',
      'updated_at'     => date('Y-m-d H:i:s'),
    ];

    $model->editItem($updates, $item_id);

    header('Content-Type: application/json'); echo json_encode(['status' => 'ok', 'message' => 'Listing updated and resubmitted for review.']);
    exit;
  }

  // ─── Partnership mobile API ─────────────────────────────────────────────

  public function fetchPartnershipPayments()
  {
    $data  = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $id    = isset($data->partnership_id) ? (int)$data->partnership_id : 0;

    if (empty($email) || $id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    $db = \Config\Database::connect();

    // Verify the partnership belongs to this email
    $partnership = $db->table('tbl_partnerships')
      ->where('id', $id)
      ->where('partner_email', $email)
      ->get()->getRow();

    if (!$partnership) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Record not found']);
      exit;
    }

    $payments = $db->table('tbl_partnership_payments')
      ->where('partnership_id', $id)
      ->orderBy('created_at', 'DESC')
      ->get()->getResult();

    header('Content-Type: application/json'); echo json_encode([
      'status'       => 'ok',
      'payments'     => $payments,
      'pledge_amount' => (float) $partnership->pledge_amount,
      'paid_amount'   => (float) $partnership->paid_amount,
      'remaining'     => max(0, (float) $partnership->pledge_amount - (float) $partnership->paid_amount),
      'currency'      => $partnership->currency,
    ]);
    exit;
  }

  public function fetchPartnershipTiers()
  {
    $data  = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    $model = new \App\Models\Partnership_model();
    $tiers = $model->getAllTiers();

    // If caller sends their email, include context about their current active/pending partnership
    $current = null;
    if ($email) {
      $db = \Config\Database::connect();
      $current = $db->table('tbl_partnerships p')
        ->select('p.id, p.tier_id, p.pledge_amount, p.paid_amount, p.currency, p.frequency, p.status, t.name AS tier_name, t.min_amount AS tier_min_amount, t.color AS tier_color')
        ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
        ->where('p.partner_email', $email)
        ->whereIn('p.status', ['active', 'pending'])
        ->orderBy('p.created_at', 'DESC')
        ->limit(1)
        ->get()->getRow();
    }

    // Tag each tier as upgrade / downgrade / current / new relative to current partnership
    $currentMinAmount = $current ? (float)($current->tier_min_amount ?? 0) : null;
    $currentTierId    = $current ? (int)($current->tier_id ?? 0) : null;
    foreach ($tiers as $t) {
      if ($currentTierId === null) {
        $t->action = 'new';
      } elseif ((int)$t->id === $currentTierId) {
        $t->action = 'renew';
      } elseif ((float)$t->min_amount > $currentMinAmount) {
        $t->action = 'upgrade';
      } else {
        $t->action = 'downgrade';
      }
    }

    header('Content-Type: application/json'); echo json_encode([
      'status'       => 'ok',
      'tiers'        => $tiers,
      'current'      => $current,
    ]);
    exit;
  }

  public function fetchMyPartnership()
  {
    $data  = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (empty($email)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    $db = \Config\Database::connect();
    $rows = $db->table('tbl_partnerships p')
      ->select('p.*, t.name AS tier_name, t.color AS tier_color, t.description AS tier_description')
      ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
      ->where('p.partner_email', $email)
      ->orderBy('p.created_at', 'DESC')
      ->get()->getResult();

    header('Content-Type: application/json'); echo json_encode([
      'status'       => 'ok',
      'partnerships' => $rows,
    ]);
    exit;
  }

  public function submitPartnershipPledge()
  {
    $data  = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (empty($email)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    $name = isset($data->partner_name) ? trim(filter_var($data->partner_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    if (empty($name)) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Name is required']);
      exit;
    }

    $pledgeAmount = isset($data->pledge_amount) ? (float)$data->pledge_amount : 0;
    if ($pledgeAmount <= 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'A valid pledge amount is required']);
      exit;
    }

    $tierId    = isset($data->tier_id) && $data->tier_id ? (int)$data->tier_id : null;
    $currency  = isset($data->currency) ? strtoupper(filter_var($data->currency, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : 'USD';
    $frequency = isset($data->frequency) ? filter_var($data->frequency, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'monthly';
    $notes     = isset($data->notes) ? filter_var($data->notes, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $phone     = isset($data->partner_phone) ? filter_var($data->partner_phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    $allowedFreq = ['one-time', 'monthly', 'quarterly', 'annually'];
    if (!in_array($frequency, $allowedFreq)) $frequency = 'monthly';

    $db = \Config\Database::connect();

    // ── Subscription guard ────────────────────────────────────────────────
    // A member may only hold one active/pending partnership at a time.
    // If one exists, they must renew (same tier) or upgrade/downgrade (different tier)
    // via updatePartnershipPledge instead of creating a new record.
    $existing = $db->table('tbl_partnerships p')
      ->select('p.id, p.tier_id, p.pledge_amount, p.paid_amount, p.currency, p.frequency, p.status, p.start_date, t.name AS tier_name, t.min_amount AS tier_min_amount, t.color AS tier_color')
      ->join('tbl_partnership_tiers t', 't.id = p.tier_id', 'left')
      ->where('p.partner_email', $email)
      ->whereIn('p.status', ['active', 'pending'])
      ->orderBy('p.created_at', 'DESC')
      ->limit(1)
      ->get()->getRow();

    if ($existing) {
      $existingTierId  = (int)($existing->tier_id ?? 0);
      $existingMinAmt  = (float)($existing->tier_min_amount ?? 0);

      // Determine what action they should take
      if ($tierId === null || $existingTierId === $tierId) {
        $action  = 'renew';
        $message = 'You are already subscribed to this tier. Use the renew option to continue your commitment.';
      } elseif ($tierId !== null) {
        // Compare the requested tier's min_amount to decide up/down
        $requestedTier = $db->table('tbl_partnership_tiers')
          ->where('id', $tierId)
          ->where('status', 'active')
          ->get()->getRow();
        $requestedMin = $requestedTier ? (float)$requestedTier->min_amount : 0;
        if ($requestedMin > $existingMinAmt) {
          $action  = 'upgrade';
          $message = 'You already have an active partnership. To move to a higher tier, use the upgrade option.';
        } else {
          $action  = 'downgrade';
          $message = 'You already have an active partnership. To move to a lower tier, use the downgrade option.';
        }
      } else {
        $action  = 'renew';
        $message = 'You already have an active partnership. Please renew or change your tier instead.';
      }

      header('Content-Type: application/json'); echo json_encode([
        'status'              => 'error',
        'code'                => 'already_subscribed',
        'message'             => $message,
        'suggested_action'    => $action,   // 'renew' | 'upgrade' | 'downgrade'
        'existing_partnership' => $existing,
      ]);
      exit;
    }

    // Link to member account if exists
    $member   = $db->table('tbl_members')->where('email', $email)->get()->getRow();
    $memberId = $member ? $member->id : null;

    $model = new \App\Models\Partnership_model();
    $model->addPartnership([
      'member_id'     => $memberId,
      'tier_id'       => $tierId,
      'partner_name'  => $name,
      'partner_email' => $email,
      'partner_phone' => $phone,
      'pledge_amount' => $pledgeAmount,
      'paid_amount'   => 0.00,
      'currency'      => $currency,
      'frequency'     => $frequency,
      'start_date'    => date('Y-m-d'),
      'status'        => 'pending',
      'notes'         => $notes,
      'created_at'    => date('Y-m-d H:i:s'),
      'updated_at'    => date('Y-m-d H:i:s'),
    ]);

    header('Content-Type: application/json'); echo json_encode([
      'status'  => $model->status,
      'message' => $model->status === 'ok'
        ? 'Partnership application submitted. Awaiting admin approval.'
        : $model->message,
    ]);
    exit;
  }

  public function updatePartnershipPledge()
  {
    $data  = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $id    = isset($data->partnership_id) ? (int)$data->partnership_id : 0;

    if (empty($email) || $id === 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Authentication required']);
      exit;
    }

    // Ownership check — only the pledging member can edit their record
    $db   = \Config\Database::connect();
    $existing = $db->table('tbl_partnerships')
      ->where('id', $id)
      ->where('partner_email', $email)
      ->get()->getRow();

    if (!$existing) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Record not found']);
      exit;
    }

    $pledgeAmount = isset($data->pledge_amount) ? (float)$data->pledge_amount : null;
    if ($pledgeAmount !== null && $pledgeAmount <= 0) {
      header('Content-Type: application/json'); echo json_encode(['status' => 'error', 'message' => 'Pledge amount must be greater than zero']);
      exit;
    }

    $allowedFreq       = ['one-time', 'monthly', 'quarterly', 'annually'];
    $allowedCurrencies = ['USD', 'GBP', 'EUR', 'NGN', 'GHS', 'KES', 'ZAR', 'CAD', 'AUD', 'INR', 'JPY', 'CNY', 'BRL', 'MXN', 'CHF', 'SEK', 'NOK', 'DKK', 'SGD', 'HKD'];

    $updates = ['updated_at' => date('Y-m-d H:i:s'), 'status' => 'pending'];

    if ($pledgeAmount !== null)
      $updates['pledge_amount'] = $pledgeAmount;

    if (isset($data->tier_id))
      $updates['tier_id'] = $data->tier_id ? (int)$data->tier_id : null;

    if (isset($data->currency)) {
      $cur = strtoupper(filter_var($data->currency, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
      if (in_array($cur, $allowedCurrencies)) $updates['currency'] = $cur;
    }

    if (isset($data->frequency)) {
      $freq = filter_var($data->frequency, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      if (in_array($freq, $allowedFreq)) $updates['frequency'] = $freq;
    }

    if (isset($data->partner_phone))
      $updates['partner_phone'] = filter_var($data->partner_phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($data->notes))
      $updates['notes'] = filter_var($data->notes, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($data->end_date) && $data->end_date)
      $updates['end_date'] = date('Y-m-d', strtotime($data->end_date));

    $model = new \App\Models\Partnership_model();
    $model->updatePartnership($id, $updates);

    header('Content-Type: application/json'); echo json_encode([
      'status'  => $model->status,
      'message' => $model->message,
    ]);
    exit;
  }
}