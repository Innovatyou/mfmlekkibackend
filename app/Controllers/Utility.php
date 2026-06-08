<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Manage_model as managemodel;
use App\Models\Settings_model as settingsmodel;

class Utility extends BaseController
{
  protected $session;

  /**
   * constructor
   */
  public function __construct()
  {
  }

  public function checkanddisconnectexpiredsubsorsendmail()
  {
    $managemodel = new managemodel();
    $managersettings = $managemodel->getManagerSettings();
    $churches = $managemodel->getAllChurches();
    foreach ($churches as $row) {
      if ($row->never_expire == 1) {
        $date1 = date_create(date('Y-m-d H:i:s'));
        $date2 = date_create($row->expiry_date);
        $diff = date_diff($date1, $date2);
        if ($diff->format("%R%a") < 0) {
          if ((abs($diff->format("%R%a")) > $managersettings->grace_period) && $row->status == 0) {
            //user is pass due, disconnet and send a mail
            $settingsmodel = new settingsmodel();
            $sub['status'] = 1;
            $settingsmodel->editchurchprofile($sub, $row->apitoken);
            $subject = "Account Deactivation";
            $message = "Your payment is past due and you may not be able to access all the services again, you need to make a payment to be reactivated.";
            $this->sendchurchemail($row->email, $row->fullname, $subject, $message);
          } else {
            //user is past due, but on grace period, send a mail
            $subject = "Account Deactivation";
            $message = " Your payment is past due, You need to make a payment on or before " . ($diff->format("%R%a") + $managersettings->grace_period) . "days to avoid disconnection.";
            $this->sendchurchemail($row->email, $row->fullname, $subject, $message);
          }
        } else {
          if ($diff->format("%R%a") == $managersettings->days_before_notify) {
            //alert user that his subscription will soon expire
            $subject = "Payment Due";
            $message = "Your subscription will be due on " . $row->expiry_date . ", You need to make a payment soon to keep enjoying our services.";
            $this->sendchurchemail($row->email, $row->fullname, $subject, $message);
          }
        }
      }
    }
    exit;
  }

  public function deletescheduledchurches()
  {
    $managemodel = new managemodel();
    $churches = $managemodel->getDeleteScheduledChurches();
    $db_tables = ["settings", "tbl_articles", "tbl_books", "tbl_branches", "tbl_chat", "tbl_chat_messages", "tbl_devotionals", "tbl_donations", "tbl_events", "tbl_fcm_token", "tbl_groups", "tbl_group_events", "tbl_group_members", "tbl_hymns", "tbl_inbox", "tbl_lists", "tbl_list_members", "tbl_livestreams", "tbl_media", "tbl_members", "tbl_messaging", "tbl_notifications", "tbl_photos", "tbl_post_comments", "tbl_post_likes", "tbl_post_pins", "tbl_prayers", "tbl_radio", "tbl_reported_comments", "tbl_social_fcm_tokens", "tbl_testimonies", "tbl_user_following", "tbl_user_posts", "tbl_verification"];
    $upload_folders = ["audios", "books", "members", "photos", "socials/chats", "socials/photos", "socials/videos", "thumbnails", "thumbnails/events"];
    foreach ($churches as $row) {
      //delete database records
      foreach ($db_tables as $tbl) {
        $managemodel->deleteChurchData($tbl, $row->apitoken);
      }
      //delete files
      foreach ($upload_folders as $uploads) {
        $this->deleteFolderFIles($uploads, $row->apitoken);
      }
      //update church status to deleted
      $info = array(
        'status' => 1,
        'isdelete' => 1,
      );
      $managemodel->editchurchdata($info, $row->id);
    }
    exit;
  }

  private function sendchurchemail($email, $churchname, $subject, $htmlContent)
  {
    $settingsmodel = new settingsmodel();
    $emailconfig = $settingsmodel->getEmailConfig();
    $this->sendEmail("Admin", $emailconfig, $email, $subject, $this->getChurchEmailTemplate($churchname, $htmlContent));
  }

  private function deleteFolderFIles($folder, $apitoken)
  {
    // Folder path to be flushed 
    $_dir = "./uploads/" . $folder . "/" . $apitoken . "/";
    if (file_exists($_dir)) {
      // Assigning files inside the directory 
      $dir = new \RecursiveDirectoryIterator(
        $_dir,
        \FilesystemIterator::SKIP_DOTS
      );
      // Reducing file search to given root 
      // directory only 
      $dir = new \RecursiveIteratorIterator(
        $dir,
        \RecursiveIteratorIterator::CHILD_FIRST
      );

      // Removing directories and files inside 
      // the specified folder 
      foreach ($dir as $file) {
        $file->isDir() ?  rmdir($file) : unlink($file);
      }
      rmdir($_dir);
    }
  }
}
