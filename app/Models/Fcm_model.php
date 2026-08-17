<?php

namespace App\Models;

use CodeIgniter\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Notification_model;

class Fcm_model extends Model
{
  protected $db;
  public $status = 'error';
  public $message = 'Error processing requested operation.';
  public $role = 0;
  private $cloudMessaging;

  public function __construct()
  {
    parent::__construct();
    $session = session();
    $this->role = $session->get('role');
    $factory = (new Factory)
      ->withServiceAccount('./uploads/firebase.json');
    $this->cloudMessaging = $factory->createMessaging();
  }

  function testnotifications()
  {
    $tokens = array_chunk($this->androidUsersTokenListing(), 1000);
    // var_dump(sizeof($tokens)); //die;
    for ($i = 0; $i < sizeof($tokens); $i++) {
      $this->createNotificationMessage($tokens);
    }
  }

  function createNotificationMessage($tokens)
  {
    $data = array('title' => "hello world", 'action' =>  "inbox", 'inbox' => json_encode([]));


    $message = CloudMessage::new();
    $message = $message->withData($data);
    $sendReport = $this->cloudMessaging->sendMulticast($message, $tokens);
    log_message('info', 'FCM multicast: {success} sent, {failures} failed', [
      'success'  => $sendReport->successes()->count(),
      'failures' => $sendReport->failures()->count(),
    ]);
  }

  function storeUserFcmToken($token)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_fcm_token');
    $token['date'] = date('Y-m-d H:i:s');
    $builder->insert($token);
    //$insert_id = $builder->insert_id();
    
    $this->message = 'token added successfully';
  }

  function updateUserFcmToken($token, $version)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_fcm_token');
    $data['app_version'] = $version;
    $builder->where('token', $token);
    $builder->update($data);
    
    $this->message = 'token updated successfully';
  }

  function androidUsersTokenListing()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_fcm_token');
    $builder->select('tbl_fcm_token.token');
    $query = $builder->get();
    //var_dump($query); die;
    $result =  $query->getResult();
    //var_dump($result); die;
    $token = [];
    foreach ($result as $res) {
      array_push($token, $res->token);
    }
    //var_dump($token); die;
    return $token;
  }

  function AllUsersSocialTokenListing($email = "null")
  {
    //$builder->select('tbl_social_fcm_tokens.token');
    //$builder->from('tbl_social_fcm_tokens');

    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_social_fcm_tokens');
    $builder->select('tbl_social_fcm_tokens.token');
    $builder->where('email !=', $email);
    $query = $builder->get();
    //var_dump($query); die;
    $result =  $query->getResult();
    //var_dump($result); die;
    $token = [];
    foreach ($result as $res) {
      array_push($token, $res->token);
    }
    //var_dump($token); die;
    return $token;
  }

  function usersSocialTokenListing($email)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_social_fcm_tokens');
    $builder->select('tbl_social_fcm_tokens.token');
    $builder->where('email', $email);
    $query = $builder->get();
    //var_dump($query); die;
    $result =  $query->getResult();
    //var_dump($result); die;
    $token = [];
    foreach ($result as $res) {
      array_push($token, $res->token);
    }
    //var_dump($token); die;
    return $token;
  }

  public function sendPushNotificationToFCMSever($API_SERVER_KEY, $title, $message)
  {

    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      // Persist notification for each user (assuming token maps to user_id, adjust as needed)
      $notificationData = [
        'user_id' => $token, // Replace with actual user_id if mapping exists
        'action' => 'generic',
        'title' => $title,
        'message' => $message,
        'payload_json' => json_encode(['title' => $title, 'message' => $message]),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    // Send push as before
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $fields = array(
        'registration_ids' => $tokensChunked[$i],
        'priority' => 10,
        'notification' => array('title' => $title, 'body' =>  $message),
      );
      $fields['time_to_live'] = 1200;
      $this->push_data($API_SERVER_KEY, $fields);
    }
  }

  public function sendUserRelatedPushNotification($API_SERVER_KEY, $email, $action)
  {
    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['email' => $email, 'action' => $action];
      $notificationData = [
        'user_id' => $token, // Replace with actual user_id if mapping exists
        'action' => $action,
        'title' => 'User Action',
        'message' => json_encode($payload),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('email' => $email, 'action' =>  $action);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }


  public function newMediaNotification($API_SERVER_KEY, $title, $media)
  {

    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $title, 'action' => 'newMedia', 'media' => $media];
      $notificationData = [
        'user_id' => $token, // Replace with actual user_id if mapping exists
        'action' => 'newMedia',
        'title' => $title,
        'message' => json_encode($media),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $title, 'action' =>  "newMedia", 'media' => json_encode($media));
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function push_event_data($API_SERVER_KEY, $event)
  {
    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $event->title, 'action' => 'Event', 'id' => $event->id];
      $notificationData = [
        'user_id' => $token,
        'action' => 'Event',
        'title' => $event->title,
        'message' => json_encode($event),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $event->title, 'action' =>  "Event", 'id' => $event->id);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function push_item_data($API_SERVER_KEY, $item, $type)
  {
    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $item->title, 'action' => $type, 'id' => $item->id];
      $notificationData = [
        'user_id' => $token,
        'action' => $type,
        'title' => $item->title,
        'message' => json_encode($item),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $item->title, 'id' => $item->id, 'action' =>  $type);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function push_inbox_data($API_SERVER_KEY, $inbox)
  {
    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $inbox->title, 'action' => 'inbox', 'inbox' => $inbox->id];
      $notificationData = [
        'user_id' => $token,
        'action' => 'inbox',
        'title' => $inbox->title,
        'message' => json_encode($inbox),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $inbox->title, 'action' =>  "inbox", 'inbox' => json_encode($inbox));
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function liveStreamsNotification($API_SERVER_KEY, $livestream)
  {
    $tokens = $this->androidUsersTokenListing();
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $livestream->title, 'action' => 'livestream', 'livestream' => $livestream->id];
      $notificationData = [
        'user_id' => $token,
        'action' => 'livestream',
        'title' => $livestream->title,
        'message' => json_encode($livestream),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $livestream->title, 'action' =>  "livestream", 'livestream' => json_encode($livestream));
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function userActionsNotification($API_SERVER_KEY, $email, $avatar, $msg)
  {
    $tokens = $this->usersSocialTokenListing($email);
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => 'New Notification', 'action' => 'social_notify', 'email' => $email, 'avatar' => $avatar, 'message' => $msg];
      $notificationData = [
        'user_id' => $token,
        'action' => 'social_notify',
        'title' => 'New Notification',
        'message' => $msg,
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => "New Notification", 'action' =>  "social_notify", 'email' => $email, 'avatar' => $avatar, 'message' => $msg);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function userConversationNotification($API_SERVER_KEY, $email, $user, $unseen, $chat)
  {
    $tokens = $this->usersSocialTokenListing($email);
    $notificationModel = new Notification_model();
    foreach ($tokens as $token) {
      $payload = ['title' => $email, 'action' => 'chat', 'chat' => $chat->id, 'user' => $user->id];
      $notificationData = [
        'user_id' => $token,
        'action' => 'chat',
        'title' => $email,
        'message' => json_encode($chat),
        'payload_json' => json_encode($payload),
        'is_read' => false,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      $notificationModel->createNotification($notificationData);
    }
    $tokensChunked = array_chunk($tokens, 1000);
    for ($i = 0; $i < sizeof($tokensChunked); $i++) {
      $data = array('title' => $email, 'action' =>  "chat", 'chat' => json_encode($chat), 'user' => json_encode($user));
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokensChunked[$i]);
    }
  }

  public function userSeenConversationNotification($API_SERVER_KEY, $email, $recipient, $chatid)
  {
    // var_dump($livestream); die;
    $tokens = array_chunk($this->usersSocialTokenListing($email), 1000);
    //var_dump($tokens); die;
    //var_dump(sizeof($tokens)); //die;
    for ($i = 0; $i < sizeof($tokens); $i++) {
      $data = array('title' => "Read Conversation", 'action' =>  "read_conversation", 'email' => $recipient, 'chatid' => $chatid);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokens[$i]);
    }
  }

  public function userTypingNotification($API_SERVER_KEY, $email, $recipient)
  {
    // var_dump($livestream); die;
    $tokens = array_chunk($this->usersSocialTokenListing($email), 1000);
    //var_dump($tokens); die;
    //var_dump(sizeof($tokens)); //die;
    for ($i = 0; $i < sizeof($tokens); $i++) {
      $data =  array('title' => "Read Conversation", 'action' =>  "user_typing", 'email' => $recipient);
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokens[$i]);
    }
  }

  public function notifyUserOnlinePresence($API_SERVER_KEY, $email, $status)
  {
    // var_dump($livestream); die;
    $tokens = array_chunk($this->AllUsersSocialTokenListing($email), 1000);
    //var_dump($tokens); die;
    //var_dump(sizeof($tokens)); //die;
    for ($i = 0; $i < sizeof($tokens); $i++) {
      $data = array('title' => "Online Status", 'action' =>  "online_status", 'email' => $email, 'status' => $status, 'last_seen' => time());
      $message = CloudMessage::new();
      $message = $message->withData($data);
      $sendReport = $this->cloudMessaging->sendMulticast($message, $tokens[$i]);
    }
  }

  private function push_data($API_SERVER_KEY, $fields)
  {
    //echo $API_SERVER_KEY; die;
    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
    $headers = array(
      'Authorization:key=' . $API_SERVER_KEY,
      'Content-Type:application/json'
    );
    // Open connection
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);
    //var_dump($result); die;
    // Close connection
    curl_close($ch);
    $res = json_decode($result);
    //var_dump($res); die;
    //var_dump($res->results[0]->error);
    if (isset($res->results[0]->error) && $res->results[0]->error != 'NotRegistered') { //NotRegistered, common error when a user uninstalls the app causing the token to be invalide
      $this->status = "error";
      $this->message = $res->results[0]->error;
    } else {
      $this->status = "ok";
      $this->message = "Message sent Successfully";
    }
  }
}
