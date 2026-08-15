<?php

namespace App\Models;

use App\Models\Basemodel;

class Socials_model extends Basemodel
{
  public $status;
  public $message;

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }


  function deleteSocialToken($token)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_social_fcm_tokens');
    $builder->where('token', $token);
    $builder->delete();
  }

  /**
   * This function is used to store user fcm token
   */
  function updateUserSocialFcmToken($token)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_social_fcm_tokens');
    $builder->insert($token);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['token_added'];
  }

  public function updateUserSettings($settings, $email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->where('email', $email);
    $builder->update($settings);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['settings_updated'];
  }

  function getuserBioInfo($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('tbl_members.email', $email);
    $query = $builder->get();
    $user = $query->getRow(0);
    if ($user) {
      $user->activated = 1;
      $user->photo = $user->thumbnail == "" ? "" : $this->request_base_url() . "/uploads/members/" . $user->thumbnail;
      $user->coverphoto = $user->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $user->coverphoto;
      return $user;
    } else {
      return null;
    }
  }

  function usersToFollowListing($page = 0, $query = "", $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
    $builder->where('tbl_members.email !=', $email);
    $builder->where('tbl_members.firstname !=', "");
    if ($query != "") {
      $builder->like('tbl_members.name', $query);
      $builder->orlike('tbl_members.lastname', $query);
      $builder->orlike('tbl_members.email', $query);
    }
    $builder->orderby('tbl_members.firstname', 'ASC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      $res->photo = $res->thumbnail == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      $res->following = 1;
    }
    return $result;
  }

  public function get_total_users($email, $query = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.id');
    $builder->where('tbl_members.email !=', $email);
    $builder->where('tbl_members.firstname !=', "");
    if ($query != "") {
      $builder->like('tbl_members.firstname', $query);
      $builder->orlike('tbl_members.lastname', $query);
      $builder->orlike('tbl_members.email', $query);
    }
    $query = $builder->get();
    $result = $query->getResult();
    return count((array) $result);
  }

  function saveUserPost($info)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['post_upload'];
  }

  public function editpost($id, $content, $visibility)
  {
    $data = ['content' => $content, 'visibility' => $visibility, 'edited' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->where('id', $id);
    $builder->update($data);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['post_edit'];
  }

  public function deletepost($id)
  {
    $data = ['deleted' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->where('id', $id);
    $builder->update($data);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['post_edit'];
  }

  public function fetch_posts($page = 0, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->select('tbl_user_posts.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_user_posts.email');
    $builder->where('tbl_user_posts.deleted', 1);
    $builder->orderby('tbl_user_posts.id', 'DESC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $res) {
      $res->photo = $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      $res->comments_count = $this->get_total_comments($res->id);
      $res->isLiked = $this->checkIfUserLikedPost($res->id, $email);
      $res->isPinned = $this->checkIfPinnedPost($res->id, $email);
      if ($res->content != "") {
        $res->content = base64_decode($res->content);
      }
      if ($res->media != "") {
        $media = json_decode($res->media);
        $res->media = [];
        foreach ($media as $mdia) {
          if ($this->get_extension($mdia) == "mp4") {
            $mdia = $this->request_base_url() . "/uploads/socials/videos/" . $mdia;
          } else {
            $mdia = $this->request_base_url() . "/uploads/socials/photos/" . $mdia;
          }
          array_push($res->media, $mdia);
        }
      }
    }
    return $result;
  }

  public function fetchUserPins($page = 0, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_pins');
    $builder->select('tbl_post_pins.*, tbl_user_posts.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_user_posts', 'tbl_user_posts.id = tbl_post_pins.post_id');
    $builder->join('tbl_members', 'tbl_members.email=tbl_user_posts.email');
    $builder->where('tbl_user_posts.deleted', 1);
    $builder->where('tbl_post_pins.email', $email);
    $builder->orderby('tbl_post_pins.date', 'DESC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $res) {
      $res->photo = $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      $res->comments_count = $this->get_total_comments($res->id);
      $res->isLiked = $this->checkIfUserLikedPost($res->id, $email);
      $res->isPinned = $this->checkIfPinnedPost($res->id, $email);
      if ($res->content != "") {
        $res->content = base64_decode($res->content);
      }
      if ($res->media != "") {
        $media = json_decode($res->media);
        $res->media = [];
        foreach ($media as $mdia) {
          if ($this->get_extension($mdia) == "mp4") {
            $mdia = $this->request_base_url() . "/uploads/socials/videos/" . $mdia;
          } else {
            $mdia = $this->request_base_url() . "/uploads/socials/photos/" . $mdia;
          }
          array_push($res->media, $mdia);
        }
      }
    }
    return $result;
  }

  public function get_user_total_pins($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_pins');
    $builder->select('tbl_post_pins.id');
    $builder->join('tbl_user_posts', 'tbl_user_posts.id = tbl_post_pins.post_id');
    $builder->where('tbl_post_pins.email', $email);
    $builder->where('tbl_user_posts.deleted', 1);
    $query = $builder->get();
    return count((array)$query->getResult());
  }

  public function likeunlikepost($id, $email, $action = "like")
  {
    $check = $this->checkIfUserLikedPost($id, $email);
    $db = \Config\Database::connect("default");
    if ($action == "unlike" && $check == true) {
      $builder = $db->table('tbl_post_likes');
      $builder->where('post_id', $id);
      $builder->where('email', $email);
      $builder->delete();

      //update total likes on media
      $builder = $db->table('tbl_user_posts');
      $builder->where('id', $id);
      $builder->set('likes_count', '`likes_count`- 1', false);
      $builder->update();
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['post_unliked'];
    } else if ($check == false) {
      $data = ['post_id' => $id, 'email' => $email, 'date' => time()];
      $builder = $db->table('tbl_post_likes');
      $builder->insert($data);

      //update total likes on media
      $builder = $db->table('tbl_user_posts');
      $builder->where('id', $id);
      $builder->set('likes_count', '`likes_count`+ 1', false);
      $builder->update();
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['post_like'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['success'];
    }
  }

  function post_likes_people($page = 0, $post  = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_likes');
    $builder->select('tbl_post_likes.email, tbl_members.*');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_likes.email');
    $builder->where('tbl_post_likes.post_id', $post);
    $builder->orderby('tbl_post_likes.date', 'DESC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }

    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $res) {
      $res->photo = $res->thumbnail == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      $res->following = 1;
    }
    if ($result) {
      return $result;
    } else {
      return [];
    }
  }

  public function getUsersPostLikesCount($post)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_likes');
    $builder->select('COUNT(*) as num');
    $builder->where('post_id', $post);
    $query = $builder->get();
    $row = $query->getRow(0);
    if (isset($row)) return $row->num;
    return 0;
  }

  public function get_total_posts($email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->select("COUNT(*) as num");
    $builder->where('deleted', 1);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function checkIfUserLikedPost($id, $email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_likes');
    $builder->select('tbl_post_likes.id');
    $builder->where('email', $email);
    $builder->where('post_id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return true;
    } else {
      return false;
    }
  }

  public function checkIfPinnedPost($id, $email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_pins');
    $builder->select('tbl_post_pins.id');
    $builder->where('email', $email);
    $builder->where('post_id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      return true;
    } else {
      return false;
    }
  }

  function fetch_user_settings($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members ');
    $builder->select('tbl_members.show_dateofbirth, tbl_members.show_phone, tbl_members.notify_follows,
                         tbl_members.notify_comments, tbl_members.notify_likes');
    $builder->where('email', $email);
    $query = $builder->get();
    return $query->getRow(0);
  }

  function getUpdatedUserProfile($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members ');
    $builder->select('tbl_members.*');
    $builder->where('tbl_members.email', $email);
    $query = $builder->get();
    $user =  $query->getRow(0);
    if ($user) {
      $user->activated = 1;
      $user->name = $user->firstname . " " . $user->lastname;
      $user->photo = $user->thumbnail == "" ? "" : $this->request_base_url() . "/uploads/socials/avatars/" . $user->thumbnail;
      $user->coverphoto = $user->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/socials/coverphotos/" . $user->coverphoto;
      return $user;
    } else {
      return null;
    }
  }

  public function saveNotificationData($itm_id, $type, $email, $user)
  {
    $data = array('itm_id' => $itm_id, 'type' => $type, 'user' => $user, 'email' => $email, 'timestamp' => time());
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->insert($data);
  }

  public function pinunpinpost($id, $email, $action = "like")
  {
    $check = $this->checkIfPinnedPost($id, $email);
    $db = \Config\Database::connect("default");
    if ($action == "unpin" && $check == true) {
      $builder = $db->table('tbl_post_pins');
      $builder->where('post_id', $id);
      $builder->where('email', $email);
      $builder->delete();
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['post_unpin'];
    } else if ($check == false) {
      $data = ['post_id' => $id, 'email' => $email, 'date' => time()];
      $builder = $db->table('tbl_post_pins');
      $builder->insert($data);
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['post_pin'];
    } else {
      $this->status = $this->applocal['ok'];
      $this->message = $this->applocal['success'];
    }
  }



  public function get_extension($file)
  {
    $array = explode('.', $file);
    return end($array);
  }


  //comments
  //comments
  function getUserPhotos($email, $res)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_members');
    $builder->select('tbl_members.thumbnail, tbl_members.coverphoto');
    $builder->where('email', $email);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      $res->photo = $res->thumbnail == "" ? "" : $this->request_base_url() . "/uploads/members/" . $row->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $row->coverphoto;
    }
    return $res;
  }


  public function makeComment($post, $email, $content)
  {
    $db = \Config\Database::connect("default");
    $data = ['post_id' => $post, 'email' => $email, 'content' => $content, 'type' => "comments", 'date' => time()];
    $builder = $db->table('tbl_post_comments');
    $builder->insert($data);
    $insertid =  $db->insertID();

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_published'];

    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.id', $insertid);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      $row->replies = 0;
      $row->name = $row->firstname . " " . $row->lastname;
      $row = $this->getUserPhotos($email, $row);
      return $row;
    } else {
      return [];
    }
  }

  public function editComment($id, $content)
  {
    $data = ['content' => $content, 'edited' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('tbl_post_comments.id', $id);
    $builder->update($data);

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_edit'];

    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      $row->replies = $this->get_total_replies($row->id);
      $row->name = $row->firstname . " " . $row->lastname;
      $row = $this->getUserPhotos($row->email, $row);
      return $row;
    } else {
      return [];
    }
  }

  public function deleteComment($id)
  {
    $data = ['deleted' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('id', $id);
    $builder->update($data);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_delete'];
  }

  public function loadcomments($post = 0, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.post_id', $post);
    if ($id != 0) {
      $builder->where('tbl_post_comments.id <', $id);
    }
    $builder->where('tbl_post_comments.deleted', 1);
    $builder->orderby('tbl_post_comments.date', 'desc');
    $builder->limit(15);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $res) {
      $res->replies = $this->get_total_replies($res->id);
      $res = $this->getUserPhotos($res->email, $res);
      $res->name = $res->firstname . " " . $res->lastname;
    }
    return $result;
  }

  public function checkIfpostHasMoreComments($post = 0, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('post_id', $post);
    if ($id != 0) {
      $builder->where('id <', $id);
    }
    $builder->where('deleted', 1);
    $query = $builder->get();
    $result = $query->getResult();
    return count((array)$result) > 15 ? true : false;
  }

  public function get_total_replies($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->select("COUNT(*) as num");
    $builder->where('deleted', 1);
    $builder->where('comment_id', $id);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function get_total_comments($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->select("COUNT(*) as num");
    $builder->where('deleted', 1);
    $builder->where('post_id', $id);
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function reportComment($id, $email, $type, $reason)
  {
    $data = ['comment_id' => $id, 'email' => $email, 'type' => $type, 'reason' => $reason, 'date' => time()];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_reported_comments');
    $builder->insert($data);
    $insertid =  $db->insertID();

    $this->deleteComment($id);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_report'];
  }

  //comment replies
  public function replyComment($comment, $email, $content)
  {
    $db = \Config\Database::connect("default");
    $data = ['comment_id' => $comment, 'email' => $email, 'content' => $content, 'type' => "replies", 'date' => time()];
    $builder = $db->table('tbl_post_comments');
    $builder->insert($data);
    $insertid =  $db->insertID();

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_published'];

    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.id', $insertid);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      $row->replies = 0;
      $row->name = $row->firstname . " " . $row->lastname;
      $row = $this->getUserPhotos($email, $row);
      return $row;
    } else {
      return [];
    }
  }

  public function editReply($id, $content)
  {
    $data = ['content' => $content, 'edited' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('tbl_post_comments.id', $id);
    $builder->update($data);

    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['comment_edit'];

    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.id', $id);
    $query = $builder->get();
    $row = $query->getRow(0);
    if ($row) {
      $row->replies = $this->get_total_replies($row->id);
      $row->name = $row->firstname . " " . $row->lastname;
      $row = $this->getUserPhotos($row->email, $row);
      return $row;
    } else {
      return [];
    }
  }

  public function deleteReply($id)
  {
    $data = ['deleted' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('tbl_post_comments.id', $id);
    $builder->update($data);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['reply_delete'];
  }

  public function loadreplies($comment = 0, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->select('tbl_post_comments.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_post_comments.email');
    $builder->where('tbl_post_comments.comment_id', $comment);
    if ($id != 0) {
      $builder->where('tbl_post_comments.id <', $id);
    }
    $builder->where('tbl_post_comments.deleted', 1);
    $builder->orderby('tbl_post_comments.date', 'desc');
    $builder->limit(15);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $res) {
      $res->replies = $this->get_total_replies($res->id);
      $res = $this->getUserPhotos($res->email, $res);
      $res->name = $res->firstname . " " . $res->lastname;
    }
    return $result;
  }

  public function checkIfCommentHaveMoreReplies($comment = 0, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_post_comments');
    $builder->where('comment_id', $comment);
    if ($id != 0) {
      $builder->where('id <', $id);
    }
    $builder->where('deleted', 1);
    $query = $builder->get();
    $result = $query->getResult();
    return count((array)$result) > 15 ? true : false;
  }

  //notifications
  function userNotifications($page = 0, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select('tbl_notifications.*');
    if ($email != "") {
      $builder->where('tbl_notifications.user', $email);
      $builder->where('tbl_notifications.type', "inbox");
    } else {
      $builder->where('tbl_notifications.type', "inbox");
    }
    if ($email != "") {
      $builder->where("(type = 'inbox' OR user = '" . $email . "')");
    } else {
      $builder->where('tbl_notifications.type', "inbox");
    }

    //
    $builder->orderby('tbl_notifications.timestamp', 'DESC');
    if ($page != 0) {
      $builder->limit(20, $page * 20);
    } else {
      $builder->limit(20);
    }

    $query = $builder->get();
    $result = $query->getResult();
    foreach ($result as $res) {
      $res->photo = "";
      $res->thumbnail = "";
      $res->firstname = "";
      $res->lastname = "";
      if ($res->email != "") {
        $user = $this->getuserBioInfo($res->email);
        if($user){
                     $res->photo = $user->photo;
                     $res->coverphoto = $user->coverphoto;
                     $res->firstname = $user->firstname;
                     $res->lastname = $user->lastname;
                     }

      }

      $res->following = 1;

      if ($res->type == "follow") {
        $res->message = $this->applocal['followed'];
      } else if ($res->type == "comment") {
        $res->message = $this->applocal['commented'];
        $res->post = $this->get_postData($res->itm_id, $email);
      } else if ($res->type == "like") {
        $res->message = $this->applocal['liked'];
        $res->post = $this->get_postData($res->itm_id, $email);
      }
    }
    return $result;
  }

  public function getUsersNotificationCount($email, $unseen = FALSE)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->select("COUNT(*) as num");

    if ($email != "") {
      $builder->where("(type = 'inbox' OR user = '" . $email . "')");
    } else {
      $builder->where('tbl_notifications.type', "inbox");
    }
    if ($unseen == TRUE) {
      $builder->where('status', 1);
    }
    $query = $builder->get();
    $result = $query->getRow(0);
    if (isset($result)) return $result->num;
    return 0;
  }

  public function setSeenNotifications($email)
  {
    $data = ['status' => 0];
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->where('user', $email);
    $builder->update($data);
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['notifications_edit'];
  }

  public function deleteNotification($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_notifications');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = $this->applocal['notifications_del'];
  }

  public function get_postData($id = 0, $email = "")
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_user_posts');
    $builder->select('tbl_user_posts.* , tbl_members.id AS userId, tbl_members.thumbnail, tbl_members.coverphoto, tbl_members.firstname, tbl_members.lastname');
    $builder->join('tbl_members', 'tbl_members.email=tbl_user_posts.email');
    $builder->where('tbl_user_posts.deleted', 1);
    $builder->where('tbl_user_posts.id', $id);


    $query = $builder->get();
    $res = $query->getRow(0);
    if ($res) {
      $res->photo = $this->request_base_url() . "/uploads/members/" . $res->thumbnail;
      $res->coverphoto = $res->coverphoto == "" ? "" : $this->request_base_url() . "/uploads/members/" . $res->coverphoto;
      $res->comments_count = $this->get_total_comments($res->id);
      $res->isLiked = $this->checkIfUserLikedPost($res->id, $email);
      $res->isPinned = $this->checkIfPinnedPost($res->id, $email);
      if ($res->content != "") {
        $res->content = base64_decode($res->content);
      }
      if ($res->media != "") {
        $media = json_decode($res->media);
        $res->media = [];
        foreach ($media as $mdia) {
          if ($this->get_extension($mdia) == "mp4") {
            $mdia = $this->request_base_url() . "/uploads/socials/videos/" . $mdia;
          } else {
            $mdia = $this->request_base_url() . "/uploads/socials/photos/" . $mdia;
          }
          array_push($res->media, $mdia);
        }
      }
    }
    return $res;
  }
}
