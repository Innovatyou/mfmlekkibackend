<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Chat_model as chatmodel;
use App\Models\Fcm_model as fcmmodel;
use App\Models\Settings_model as settingsmodel;

class Chat extends BaseController
{


  /**
   * constructor
   */
  public function __construct()
  {
  }

  public function fetch_user_chats()
  {
    $data = $this->get_data();
    $results = [];
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $count = 0;
    if (isset($data->count)) {
      $count = $data->count;
    }
    $chatmodel = new chatmodel();
    $results = $chatmodel->getUsersChat($email, $count);
    header('Content-Type: application/json'); echo json_encode(array("chatsList" => $results));
    exit;
  }

  public function fetch_user_partner_chat()
  {
    $data = $this->get_data();
    //var_dump($data);
    $results = [];
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $chatmodel = new chatmodel();
    $results = $chatmodel->fetch_user_partner_chat($email, $partner);
    if ($results) {
      header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "chat" => $results));
    } else {
      header('Content-Type: application/json'); echo json_encode(array("status" => "none"));
    }
    exit;
  }

  public function checkfornewmessages()
  {
    $data = $this->get_data();
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $date = isset($data->date) ? filter_var($data->date, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 1602063634;
    $chatmodel = new chatmodel();
    $results = $chatmodel->checkfornewmessages($email, $date);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "chats" => $results));
    exit;
  }

  public function load_more_chats()
  {
    $data = $this->get_data();
    $chat_id = isset($data->chatId) ? filter_var($data->chatId, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $count = isset($data->count) ? filter_var($data->count, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 20;
    //print($count); die;
    $chatmodel = new chatmodel();
    if ($chat_id == 0) {
      //first we check if a chat have been initiated before
      //between both users and get the chat id
      $chat_id = $chatmodel->get_user_chatID_if_exists($email, $partner);
    }
    $results = $chatmodel->get_chat_messages($chat_id, $email, intval($count));
    $have_more_content = $chatmodel->chats_have_more_content($chat_id, $email, intval($count));
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "chats" => $results, "have_more_content" => $have_more_content));
    exit;
  }

  public function on_seen_conversation()
  {
    $data = $this->get_data();
    //var_dump($data);
    $chatid = isset($data->chatid) ? filter_var($data->chatid, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $chatmodel = new chatmodel();
    $fcmmodel = new fcmmodel();
    $settingsmodel = new settingsmodel();
    if ($chatid == 0) {
      //first we check if a chat have been initiated before
      //between both users and get the chat id
      $chatid = $chatmodel->get_user_chatID_if_exists($email, $partner);
    }
    if ($chatid != 0) {
      $chatmodel->on_seen_conversation($chatid, $email);
      //notify user of conversation read
      $server_key = $settingsmodel->getFcmServerKey();
      $fcmmodel->userSeenConversationNotification($server_key, $partner, $email, $chatid);
    }
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }

  public function on_user_typing()
  {
    $data = $this->get_data();
    //var_dump($data);
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $fcmmodel = new fcmmodel();
    $settingsmodel = new settingsmodel();

    $server_key = $settingsmodel->getFcmServerKey();
    $fcmmodel->userTypingNotification($server_key, $partner, $email);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }

  public function update_user_online_status()
  {
    $data = $this->get_data();
    //var_dump($data);
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $status = isset($data->status) ? filter_var($data->status, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 1;
    $chatmodel = new chatmodel();
    $fcmmodel = new fcmmodel();
    $settingsmodel = new settingsmodel();
    $chatmodel->updateUserOnlineStatus($email, $status);
    $server_key = $settingsmodel->getFcmServerKey();
    $fcmmodel->notifyUserOnlinePresence($server_key, $email, $status);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }

  public function save_user_conversation()
  {
    $date = time();
    $chat_id = null !== $this->request->getVar('chat_id') ? filter_var($this->request->getVar('chat_id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $sender = null !== $this->request->getVar('sender') ? filter_var($this->request->getVar('sender'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $recipient = null !== $this->request->getVar('recipient') ? filter_var($this->request->getVar('recipient'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $msg_reciept = null !== $this->request->getVar('msg_reciept') ? filter_var($this->request->getVar('msg_reciept'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : time();
    $msg_owner = null !== $this->request->getVar('msg_owner') ? filter_var($this->request->getVar('msg_owner'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $message = null !== $this->request->getVar('content') ? filter_var($this->request->getVar('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $chatmodel = new chatmodel();
    $fcmmodel = new fcmmodel();
    $settingsmodel = new settingsmodel();
    if ($chat_id == 0) {
      //first we check if a chat have been initiated before
      //between both users and get the chat id
      $chat_id = $chatmodel->get_user_chatID_if_exists($sender, $recipient);
      if ($chat_id == 0) {
        $info = array(
          'email1' => $sender,
          'email2' => $recipient,
          'last_message_time' => $date
        );
        $chat_id = $chatmodel->createUsersChatID($info);
      }
    } else {
      $chatmodel->updateChatIDLastMessageTime($chat_id, $date);
    }
    $attachment = "";
    if (!empty($_FILES['photo'])) {
      $upload = $this->upload_file();
      //var_dump($upload); die;
      if ($upload[0] == 'ok') {
        $attachment =  $upload[1];
      } else {
        header('Content-Type: application/json'); echo json_encode(array("status" => "error", "msg" => $upload[1]));
        exit;
      }
    }

    //check if this user is blocked from sending messages
    $isUserBlocked1 = $chatmodel->verifyIfPartnerIsBlocked($sender, $recipient);
    $isUserBlocked2 = $chatmodel->verifyIfPartnerIsBlocked($recipient, $sender);

    //save message for sender
    $msg1 = array(
      'chat_id' => $chat_id,
      'message' => $message,
      'attachment' => $attachment,
      'sender' => $sender,
      'msg_reciept' => $msg_reciept,
      'msg_owner' => $msg_owner,
      'date' => $date
    );
    //save for sender
    $chatmodel->saveUserChatConversation($msg1);

    //if none of the users blocked the other, we save and send notification
    if ($isUserBlocked1 != 0 && $isUserBlocked2 != 0) {
      //save message for reciever
      $msg2 = array(
        'chat_id' => $chat_id,
        'message' => $message,
        'attachment' => $attachment,
        'sender' => $sender,
        'msg_reciept' => $msg_reciept,
        'msg_owner' => $recipient,
        'date' => $date
      );
      //save for recipient
      $converseID = $chatmodel->saveUserChatConversation($msg2);
      $unseen = $chatmodel->get_unseen_messages($chat_id, $recipient);
      //send notification to recipient
      $chatsender = $chatmodel->getRecipientDetails($sender);
      /*$notificationmessage = "Sent a photo";
        if($message!=""){
          $notifcationmessage = substr(base64_decode($message),100);
        }*/
      $chat = $chatmodel->getUserLastConversation($converseID);
      $server_key = $settingsmodel->getFcmServerKey();
      $fcmmodel->userConversationNotification($server_key, $recipient, $chatsender, $unseen, $chat);
    }
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok", "chatid" => $chat_id));
    exit;
  }

  function delete_selected_chat_messages()
  {
    $data = $this->get_data();
    //var_dump($data);
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $chatid = isset($data->chatid) ? filter_var($data->chatid, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $msgReciepts = $data->msgReciepts;

    $chatmodel = new chatmodel();
    if ($chatid == 0) {
      //first we check if a chat have been initiated before
      //between both users and get the chat id
      $chatid = $chatmodel->get_user_chatID_if_exists($email, $partner);
    }
    $chat = $chatmodel->delete_selected_chat_messages($email, $chatid, $msgReciepts);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }

  function clear_user_conversation()
  {
    $data = $this->get_data();
    //var_dump($data);
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $chatid = isset($data->chatid) ? filter_var($data->chatid, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $chatmodel = new chatmodel();
    if ($chatid == 0) {
      //first we check if a chat have been initiated before
      //between both users and get the chat id
      $chatid = $chatmodel->get_user_chatID_if_exists($email, $partner);
    }
    $chat = $chatmodel->clear_user_chat_messages($email, $chatid);
    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }

  function blockUnblockUser()
  {
    $data = $this->get_data();
    //var_dump($data);
    $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $partner = isset($data->partner) ? filter_var($data->partner, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $status = isset($data->status) ? filter_var($data->status, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 1;

    $chatmodel = new chatmodel();

    if ($status == 0) {
      $info = array(
        'blocked_user' => $partner,
        'blocked_by' => $email,
      );
      $chatmodel->blockUser($info);
    } else {
      $chatmodel->unblockUser($partner, $email);
    }

    header('Content-Type: application/json'); echo json_encode(array("status" => "ok"));
    exit;
  }


  public function upload_file()
  {
    if (!file_exists('./uploads/socials/chats/')) {
      mkdir('./uploads/socials/chats/', 0777, true);
    }
    $path = $_FILES["photo"]['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $new_name = uniqid() . "_photo_" . time() . "." . $ext;
    //var_dump($new_name); die;
    helper(['form', 'url']);
    $input = $this->validate([
      'photo' => [
        'uploaded[photo]',
        'mime_in[photo,image/jpg,image/jpeg,image/png,image/JPG,image/PNG]',
        'max_size[photo,100024]',
      ]
    ]);
    if (!$input) {

      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()['photo']];
    } else {
      $img = $this->request->getFile('photo');
      $img->move('./uploads/socials/chats/', $new_name);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
