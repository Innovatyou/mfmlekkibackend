<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Socials_model as socialsmodel;

class Socials extends BaseController
{

  public $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    $this->apitoken = $this->check_headers();
  }

  function userBioInfo()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $viewer = isset($data->viewer) ? filter_var($data->viewer, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $user = $socialsmodel->getuserBioInfo($email, $this->apitoken);
    $post_count = $socialsmodel->get_total_posts($email, $this->apitoken);
    $followers_count = 0;
    $following_count = 0;
    $isFollowing = 1;
    if ($user) {
      if ($user->dob != "") {
        $date = date_create($user->dob);
        $user->dob = date_format($date, 'jS F Y');
      }
      //do some checks here
      if ($email != $viewer) {
        if ($user->show_phone != 0) {
          $user->phonenumber = "";
        }
        if ($user->show_dateofbirth != 0) {
          if ($user->dob != "") {
            $date = date_create($user->dob);
            $user->dob = date_format($date, 'jS F');
          }
        }
      }
    }

    echo json_encode(array(
      "status" => "ok", "user" => $user, "post_count" => $post_count, "followers_count" => $followers_count, "following_count" => $following_count, "isFollowing" => $isFollowing
    ));
    exit;
  }

  function userFollowPostCount()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $viewer = isset($data->viewer) ? filter_var($data->viewer, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

    $post_count = $socialsmodel->get_total_posts($email, $this->apitoken);
    $followers_count = $socialsmodel->getUsersFollowersCount($email, $this->apitoken);
    $following_count = $socialsmodel->getUsersFollowingCount($email, $this->apitoken);
    $isFollowing = $socialsmodel->getFollowStatus($email, $viewer, $this->apitoken);

    echo json_encode(array(
      "status" => "ok", "post_count" => $post_count, "followers_count" => $followers_count, "following_count" => $following_count, "isFollowing" => $isFollowing
    ));
    exit;
  }

  //fetch audios/videos
  function fetch_posts()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $results = $socialsmodel->fetch_posts($page, $email, $this->apitoken);
    $total_items = $socialsmodel->get_total_posts($email, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "posts" => $results, "isLastPage" => $isLastPage));
    exit;
  }


  function fetchUserPins()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $results = $socialsmodel->fetchUserPins($page, $email, $this->apitoken);
    $total_items = $socialsmodel->get_user_total_pins($email, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "posts" => $results, "isLastPage" => $isLastPage));
    exit;
  }


  function fetchUserPosts()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $viewer = isset($data->viewer) ? filter_var($data->viewer, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $me = FALSE;
    if ($email == $viewer) {
      $me = TRUE;
    }
    $results = $socialsmodel->fetch_user_posts($page, $email, $viewer, $me, $this->apitoken);
    $total_items = $socialsmodel->get_user_total_posts($email, $me, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;

    echo json_encode(array("status" => "ok", "posts" => $results, "isLastPage" => $isLastPage));
    exit;
  }


  //process user like or unlike media
  public function likeunlikepost()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $count = 0;
    if (!empty($data)) {
      $user = isset($data->user) ? filter_var($data->user, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
      $action = isset($data->action) ? filter_var($data->action, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($email != "" && $id != 0) {
        $socialsmodel->likeunlikepost($id, $email, $action, $this->apitoken);
        $count = $socialsmodel->getUsersPostLikesCount($id, $this->apitoken);
        if ($action == "like") {
          $this->check_notify_user($id, "like", $user, $email, $this->apitoken);
        }
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message, "count" => $count));
    exit;
  }


  public function pinunpinpost()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
      $action = isset($data->action) ? filter_var($data->action, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($email != "" && $id != 0) {
        $socialsmodel->pinunpinpost($id, $email, $action, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  //fetch audios/videos
  function get_users_to_follow()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $query = isset($data->query) ? filter_var($data->query, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }

    $results = $socialsmodel->usersToFollowListing($page, $query, $email, $this->apitoken);
    $total_items = $socialsmodel->get_total_users($email, $query, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;

    echo json_encode(array("status" => "ok", "users" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  //fetch audios/videos
  function users_follow_people()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $option = isset($data->option) ? filter_var($data->option, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $user = isset($data->user) ? filter_var($data->user, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    if ($option == "followers") {
      $results = $socialsmodel->users_followers_people($page, $user, $email, $this->apitoken);
      $total_items = $socialsmodel->getUsersFollowersCount($user, $this->apitoken);
    } else {
      $results = $socialsmodel->users_following_people($page, $user, $email, $this->apitoken);
      $total_items = $socialsmodel->getUsersFollowingCount($user, $this->apitoken);
    }
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "users" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  function post_likes_people()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $post = isset($data->post) ? filter_var($data->post, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $results = $socialsmodel->post_likes_people($page, $post, $this->apitoken);
    $total_items = $socialsmodel->getUsersPostLikesCount($post, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "users" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  function userNotifications()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $isLastPage = false;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $page = 0;
    if (isset($data->page)) {
      $page = $data->page;
    }
    $results = $socialsmodel->userNotifications($page, $email, $this->apitoken);
    $total_items = $socialsmodel->getUsersNotificationCount($email, FALSE, $this->apitoken);
    $isLastPage = (($page + 1) * 20) >= $total_items;
    echo json_encode(array("status" => "ok", "notifications" => $results, "isLastPage" => $isLastPage));
    exit;
  }

  function getUnSeenNotifications()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $total_items = $socialsmodel->getUsersNotificationCount($email, TRUE, $this->apitoken);
    echo json_encode(array("status" => "ok", "count" => $total_items));
    exit;
  }

  function follow_unfollow_user()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $user = isset($data->user) ? filter_var($data->user, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $follower = isset($data->follower) ? filter_var($data->follower, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $action = isset($data->action) ? filter_var($data->action, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

    if ($user == "" || $follower == "") {
      echo json_encode(array("status" => "error", "msg" => "No matching users found."));
      exit;
    }

    if ($action == "follow") {
      $info['user_email'] = $user;
      $info['follower_email'] = $follower;
      $info['apitoken'] = $this->apitoken;
      $socialsmodel->followUser($info);
      $this->check_notify_user(0, "follow", $user, $follower, $this->apitoken);
      echo json_encode(array("status" => "ok", "action" => $action));
    } else if ($action == "unfollow") {
      $socialsmodel->unfollowUser($user, $follower);
      echo json_encode(array("status" => "ok", "action" => $action));
    }
    exit;
  }

  function update_user_settings()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    //var_dump($data); die;
    $show_dateofbirth = isset($data->show_dateofbirth) ? filter_var($data->show_dateofbirth, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 1;
    $show_phone = isset($data->show_phone) ? filter_var($data->show_phone, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 1;
    $notify_follows = isset($data->notify_follows) ? filter_var($data->notify_follows, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $notify_comments = isset($data->notify_comments) ? filter_var($data->notify_comments, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $notify_likes = isset($data->notify_likes) ? filter_var($data->notify_likes, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $settings = array(
      "show_dateofbirth" => $show_dateofbirth, "show_phone" => $show_phone, "notify_follows" => $notify_follows, "notify_comments" => $notify_comments, "notify_likes" => $notify_likes
    );

    $socialsmodel->updateUserSettings($settings, $email, $this->apitoken);
    echo json_encode(array(
      "status" => $socialsmodel->status, "msg" => $socialsmodel->message
    ));
    exit;
  }

  function fetch_user_settings()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

    $user = $socialsmodel->fetch_user_settings($email, $this->apitoken);
    echo json_encode(array("status" => "ok", "user" => $user));
    exit;
  }


  function updateUserSocialFcmToken()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    //var_dump($data); die;
    if (isset($data->token) && $data->token != "" && isset($data->email) && $data->email != "") {
      $token = $data->token;
      $email = $data->email;
      $data = array("token" => $token, "email" => $email, "apitoken" => $this->apitoken);
      //delete existing token
      $socialsmodel->deleteSocialToken($token, $this->apitoken);
      //add new
      $socialsmodel->updateUserSocialFcmToken($data);
    }
    echo json_encode(array(
      "status" => $socialsmodel->status, "msg" => $socialsmodel->message
    ));
    exit;
  }

  function updateUserProfile()
  {
    $socialsmodel = new socialsmodel();
    $email = $this->request->getVar('email');
    $fullname = $this->request->getVar('fullname');
    $dob = $this->request->getVar('dob');
    $phone = $this->request->getVar('phone');
    $gender = $this->request->getVar('gender');
    $location = $this->request->getVar('location');
    $qualification = $this->request->getVar('qualification');
    $about_me = $this->request->getVar('about_me');
    $facebook = $this->request->getVar('facebook');
    $twitter = $this->request->getVar('twitter');
    $linkedln = $this->request->getVar('linkedln');
    $notify_token = $this->request->getVar('notify_token');

    $info = array(
      'email' => $email,
      'dob' => $dob,
      'phone' => $phone,
      'gender' => $gender,
      'location' => $location,
      'qualification' => $qualification,
      'about_me' => $about_me,
      'facebook' => $facebook,
      'twitter' => $twitter,
      'linkdln' => $linkedln,
      'notify_token' => $notify_token
    );

    $name = array(
      'name' => $fullname
    );

    if (!empty($_FILES['avatar'])) {
      //var_dump($_FILES['avatar']);
      $upload = $this->upload_file("avatar");
      if ($upload[0] == 'ok') {
        $info['avatar'] =  $upload[1];
      } else {
        echo json_encode(array("status" => "error", "msg" => $upload[1]));
        exit;
      }
    }

    if (!empty($_FILES['cover_photo'])) {
      $upload = $this->upload_file("cover_photo");
      if ($upload[0] == 'ok') {
        $info['cover_photo'] =  $upload[1];
      } else {
        echo json_encode(array("status" => "error", "msg" => $upload[1]));
        exit;
      }
    }


    $status = $socialsmodel->getUserSocialProfile($email);

    if ($status == TRUE) {
      $socialsmodel->editUserProfile($info, $email);
      $socialsmodel->editUserName($name, $email);
    } else {
      $socialsmodel->addNewUserProfile($info);
      $socialsmodel->editUserName($name, $email);
    }
    $follow_status = $socialsmodel->getFollowStatus($email, $email);
    if ($follow_status == 1) {
      $foldata['user_email'] = $email;
      $foldata['follower_email'] = $email;
      $foldata['_ignore'] = 1;
      $socialsmodel->followUser($foldata);
    }
    $user = $socialsmodel->getUpdatedUserProfile($email, $this->apitoken);

    echo json_encode(array("status" => "ok", "msg" => "Profile was updated successfully", "user" => $user));
    exit;
  }

  public function editpost()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    //var_dump($data); die;
    if (!empty($data)) {
      $content = isset($data->content) ? filter_var($data->content, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $visibility = isset($data->visibility) ? filter_var($data->visibility, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "public";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($content != "" && $id != "") {

        $socialsmodel->editpost($id, $content, $visibility, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  public function deletepost()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if (!empty($data)) {
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($id != "") {

        $socialsmodel->deletepost($id, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  public function deleteNotification()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();

    if (!empty($data)) {
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($id != "") {
        $socialsmodel->deleteNotification($id, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  public function setSeenNotifications()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();

    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($email != "") {
        $socialsmodel->setSeenNotifications($email, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  function make_post()
  {
    $socialsmodel = new socialsmodel();
    $email = $this->request->getVar('email');
    $text = $this->request->getVar('content');
    $visibility = $this->request->getVar('visibility');
    $uploaded_files = $this->do_post_uploads();

    $post = array(
      'apitoken' => $this->apitoken,
      'email' => $email,
      'content' => $text,
      'visibility' => $visibility,
      'timestamp' => time(),
      'media' => json_encode($uploaded_files)
    );
    //var_dump($post); die;
    $socialsmodel->saveUserPost($post);
    echo json_encode(array("status" => "ok"));
    exit;
  }

  public function upload_file($file)
  {
    $path = $_FILES[$file]['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($file == "avatar") {
      $new_name = uniqid() . "_avatar_" . time() . "." . $ext;
      $config['file_name'] = $new_name;
      $config['upload_path'] = './uploads/socials/avatars';
    } else {
      $new_name = uniqid() . "_cover_" . time() . "." . $ext;
      $config['file_name'] = $new_name;
      $config['upload_path']   = './uploads/socials/coverphotos';
    }
    $config['max_size']             = 10000;
    $config['allowed_types']        = '*';
    $config['overwrite'] = TRUE; //overwrite thumbnail
    $this->load->library('upload');
    $this->upload->initialize($config);
    //$this->upload->initialize($config);
    //var_dump($config);
    //$this->load->library('upload', $config);
    if (!$this->upload->do_upload($file)) {
      //$error = array('error' => $this->upload->display_errors());
      return ['error', strip_tags($this->upload->display_errors())];
    } else {
      $image_data = $this->upload->data();
      return ['ok', $new_name];
    }
    exit;
  }

  public function do_post_uploads()
  {
    if (!file_exists('./uploads/socials/photos/' . $this->apitoken)) {
      mkdir('./uploads/socials/photos/' . $this->apitoken, 0777, true);
    }
    if (!file_exists('./uploads/socials/videos/' . $this->apitoken)) {
      mkdir('./uploads/socials/videos/' . $this->apitoken, 0777, true);
    }
    $countfiles = count($_FILES);
    $upload_files = [];
    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $filedata = $_FILES['files_' . $i];
      $path = $filedata['name'];
      $ext = pathinfo($path, PATHINFO_EXTENSION);

      if ($filedata['type'] == "video/mp4") {
        $new_name = uniqid() . "_video_" . time() . "." . $ext;
        $upload_path = './uploads/socials/videos/' . $this->apitoken;
      } else {
        $new_name = uniqid() . "_photo_" . time() . "." . $ext;
        $upload_path   = './uploads/socials/photos/' . $this->apitoken;
      }
      //validate
      $input = $this->validate([
        'cover_photo' => [
          'uploaded[files_' . $i . ']',
          'mime_in[files_' . $i . ',video/mp4,image/jpg,image/jpeg,image/png]',
          'max_size[files_' . $i . ',100024]',
        ]
      ]);
      if (!$input) {
        //return ['error',$this->validator->getErrors()['files_'.$i]];
        echo json_encode(array("status" => "error", "msg" => $this->validator->getErrors()['files_' . $i]));
        exit;
      } else {
        $img = $this->request->getFile('files_' . $i);
        $img->move($upload_path, $new_name);
        array_push($upload_files, $img->getName());
      }
    }

    return $upload_files;
  }

  //comments and replies
  public function makecomment()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if (!empty($data)) {
      $user = isset($data->user) ? filter_var($data->user, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $content = isset($data->content) ? filter_var($data->content, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $post = isset($data->post) ? filter_var($data->post, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($email != "" && $post != "" && $content != "") {
        $comment = $socialsmodel->makeComment($post, $email, $content, $this->apitoken);
        $total_count = $socialsmodel->get_total_comments($post, $this->apitoken);
        $this->check_notify_user($post, "comment", $user, $email, $this->apitoken);
      }
    }
    echo json_encode(array(
      "status" => $socialsmodel->status, "message" => $socialsmodel->message,
      "comment" => $comment, "total_count" => $total_count
    ));
    exit;
  }

  public function editcomment()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comment = [];
    if (!empty($data)) {
      $content = isset($data->content) ? filter_var($data->content, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($content != "" && $id != "") {
        $comment = $socialsmodel->editComment($id, $content, $this->apitoken);
      }
    }
    echo json_encode(array(
      "status" => $socialsmodel->status, "message" => $socialsmodel->message,
      "comment" => $comment
    ));
    exit;
  }

  public function deletecomment()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if (!empty($data)) {
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $post = isset($data->post) ? filter_var($data->post, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($id != "") {
        $socialsmodel->deleteComment($id, $this->apitoken);
        $total_count = $socialsmodel->get_total_comments($post, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message, "total_count" => $total_count));
    exit;
  }

  function loadcomments()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $total_count = 0;
    http_response_code(404);
    $id = 0;
    if (isset($data->id)) {
      $id = $data->id;
    }

    $post = 0;
    if (isset($data->post)) {
      $post = $data->post;
    }

    $results = $socialsmodel->loadcomments($post, $id, $this->apitoken);
    $has_more = $socialsmodel->checkIfpostHasMoreComments($post, $id, $this->apitoken);
    $total_count = $socialsmodel->get_total_comments($post, $this->apitoken);
    if (count((array)$results) > 0) {
      http_response_code(200);
    }
    echo json_encode(array("status" => "ok", "comments" => $results, "has_more" => $has_more, "total_count" => $total_count));
    exit;
  }

  public function reportcomment()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $type = isset($data->type) ? filter_var($data->type, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $reason = isset($data->reason) ? filter_var($data->reason, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      if ($email != "" && $type != "" && $id != "") {
        $socialsmodel->reportComment($id, $email, $type, $reason, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message));
    exit;
  }

  //comment replies
  public function replycomment()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $reply = [];
    $total_count = 0;
    if (!empty($data)) {
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $content = isset($data->content) ? filter_var($data->content, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $comment = isset($data->comment) ? filter_var($data->comment, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($email != "" || $content != "") {
        $reply = $socialsmodel->replyComment($comment, $email, $content, $this->apitoken);
        $total_count = $socialsmodel->get_total_replies($comment, $this->apitoken);
      }
    }
    echo json_encode(array(
      "status" => $socialsmodel->status, "message" => $socialsmodel->message,
      "comment" => $reply, "total_count" => $total_count
    ));
    exit;
  }

  public function editreply()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if (!empty($data)) {
      $content = isset($data->content) ? filter_var($data->content, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($content != "" || $id != "") {
        $comment = $socialsmodel->editReply($id, $content, $this->apitoken);
      }
    }
    echo json_encode(array(
      "status" => $socialsmodel->status, "message" => $socialsmodel->message,
      "comment" => $comment
    ));
    exit;
  }

  public function deletereply()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $comments = [];
    if (!empty($data)) {
      $id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $comment_id = isset($data->comment) ? filter_var($data->comment, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      if ($id != "") {
        $socialsmodel->deleteReply($id, $this->apitoken);
        $total_count = $socialsmodel->get_total_replies($comment_id, $this->apitoken);
      }
    }
    echo json_encode(array("status" => $socialsmodel->status, "message" => $socialsmodel->message, "total_count" => $total_count));
    exit;
  }

  function loadreplies()
  {
    $socialsmodel = new socialsmodel();
    $data = $this->get_data();
    $results = [];
    $total_count = 0;
    http_response_code(404);
    $id = 0;
    if (isset($data->id)) {
      $id = $data->id;
    }

    $comment = 0;
    if (isset($data->comment)) {
      $comment = $data->comment;
    }
    $results = $socialsmodel->loadreplies($comment, $id, $this->apitoken);
    $has_more = $socialsmodel->checkIfCommentHaveMoreReplies($comment, $id, $this->apitoken);
    $total_count = $socialsmodel->get_total_replies($comment, $this->apitoken);
    if (count((array)$results) > 0) {
      http_response_code(200);
    }
    echo json_encode(array("status" => "ok", "comments" => $results, "has_more" => $has_more, "total_count" => $total_count));
    exit;
  }
}
