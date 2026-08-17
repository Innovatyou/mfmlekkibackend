<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Settings_model as settingsmodel;

class Settings extends BaseController
{
  protected $role = 0;

  /**
   * constructor
   */
  public function __construct()
  {
    $session = session();
    $this->role = $session->get('role');
  }

  public function index()
  {
    $settingsmodel = new settingsmodel();
    $this->viewdata['settings'] = $settingsmodel->getSettings();
    if (!$this->viewdata['settings']) {
      $data = array(
        'app_login' => 0, 'allow_downloads' => 0, 'join_groups' => 0, 'post_prayer' => 0, 'auto_approve_group_membership' => 1, 'post_testimony' => 0, 'auto_approve_prayer' => 1, 'auto_approve_testimony' => 1, 'facebook' => "", 'twitter' => "", 'instagram' => "", 'youtube' => "", 'website' => "", 'twilio_account_sid' => "", 'twilio_auth_token' => "", 'twilio_phonenumber' => "", 'termi_sender_id' => "", 'termi_apikey' => "", 'prefered_gateway' => "paypal, stripe, flutterwaves, paystack", 'book_payment_gateway' => "paystack", 'flutterwaves_api_key' => "", 'paystack_api_key' => "", 'currency_code' => "", 'donations_link' => "", 'features' => "bible,audiomessages, videomessages, donations, livestreams, events, articles, hymns, radio, photos, groups, prayer, testimony, devotionals, notes, books, gosocial", 'churchname' => "", 'terms' => "", 'privacy' => "", 'aboutus' => ""
      );
      $data['fcm_server_key'] = "";
      $settingsmodel = new settingsmodel();
      $settingsmodel->addNewChurchSettings($data);
      $this->viewdata['settings'] = $settingsmodel->getSettings();
    }
    return $this->view("settings", $this->viewdata);
  }


  public function updatesettings()
  {
    $session = session();
    $features = "";
    $formfeatures = $this->request->getVar('features');
    if (is_array($formfeatures) && !empty($formfeatures)) {
      foreach ($formfeatures as $val) {
        if (is_array($val)) {
          $features .= (array_values($val)[0]) . ", ";
        } elseif (is_string($val)) {
          $features .= $val . ", ";
        }
      }
      $features = rtrim($features, ", ");
    } else {
      $features = "";
    }

    $prefered_gateway = "";
    $formfeatures = $this->request->getVar('prefered_gateway');
    if (is_array($formfeatures) && !empty($formfeatures)) {
      foreach ($formfeatures as $val) {
        if (is_array($val)) {
          $prefered_gateway .= (array_values($val)[0]) . ", ";
        } elseif (is_string($val)) {
          $prefered_gateway .= $val . ", ";
        }
      }
      $prefered_gateway = rtrim($prefered_gateway, ", ");
    } else {
      $prefered_gateway = "";
    }

    $data = array(
      'app_login' => $this->request->getVar('app_login'), 'allow_downloads' => $this->request->getVar('allow_downloads'), 'join_groups' => $this->request->getVar('join_groups'), 'post_prayer' => $this->request->getVar('post_prayer'), 'auto_approve_group_membership' => $this->request->getVar('auto_approve_group_membership'), 'post_testimony' => $this->request->getVar('post_testimony'), 'auto_approve_prayer' => $this->request->getVar('auto_approve_prayer'), 'auto_approve_testimony' => $this->request->getVar('auto_approve_testimony'), 'facebook' => $this->request->getVar('facebook'), 'twitter' => $this->request->getVar('twitter'), 'instagram' => $this->request->getVar('instagram'), 'youtube' => $this->request->getVar('youtube'), 'website' => $this->request->getVar('website'), 'twilio_account_sid' => $this->request->getVar('twilio_account_sid'), 'twilio_auth_token' => $this->request->getVar('twilio_auth_token'), 'twilio_phonenumber' => $this->request->getVar('twilio_phonenumber'), 'termi_sender_id' => $this->request->getVar('termi_sender_id'), 'termi_apikey' => $this->request->getVar('termi_apikey'), 'prefered_gateway' => $prefered_gateway, 'flutterwaves_api_key' => $this->request->getVar('flutterwaves_api_key'), 'paypal_client' => $this->request->getVar('paypal_client'),
                   'mail_port' => $this->request->getVar('mail_port'), 'mail_protocol' => $this->request->getVar('mail_protocol'), 'mail_smtp_host' => $this->request->getVar('mail_smtp_host'), 'mail_password' => $this->request->getVar('mail_password'), 'mail_username' => $this->request->getVar('mail_username'),
      'paypal_secret' => $this->request->getVar('paypal_secret'),
      'stripe_public' => $this->request->getVar('stripe_public'),
      'stripe_secret' => $this->request->getVar('stripe_secret'),
      'thankyou' => $this->request->getVar('thankyou'),
      'paystack_api_key' => $this->request->getVar('paystack_api_key'), 'currency_code' => $this->request->getVar('currency_code'), 'donations_link' => $this->request->getVar('donations_link'), 'book_payment_gateway' => $this->request->getVar('book_payment_gateway') ?: 'paystack', 'marketplace_currency' => $this->request->getVar('marketplace_currency') ?: 'USD', 'features' => $features, 'churchname' => $this->request->getVar('churchname'), 'terms' => $this->request->getVar('terms'), 'privacy' => $this->request->getVar('privacy'), 'aboutus' => $this->request->getVar('aboutus')
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $data['donationslogo'] =  $upload[1];
      } else {
        $session->setFlashdata('error', $upload[1]['thumbnail']);
        return redirect()->to(base_url() . '/settings');
      }
    }

    $settingsmodel = new settingsmodel();
    $settingsmodel->updateSettings($data);

    $session->setFlashdata('success', "Settings updated successfully.");
    return redirect()->to(base_url() . '/settings');
  }

  public function terms()
  {
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    $this->viewdata['churchname'] = $settings ? $settings->churchname : "";
    $this->viewdata['title'] = "Terms & Conditions";
    $this->viewdata['content'] = $settings ? $settings->terms : "";
    return view("content", $this->viewdata);
  }

  public function privacy()
  {
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    $this->viewdata['churchname'] = $settings ? $settings->churchname : "";
    $this->viewdata['title'] = "Privacy Policy";
    $this->viewdata['content'] = $settings ? $settings->privacy : "";
    return view("content", $this->viewdata);
  }

  public function aboutus()
  {
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings();
    $this->viewdata['churchname'] = $settings ? $settings->churchname : "";
    $this->viewdata['title'] = "About Us";
    $this->viewdata['content'] = $settings ? $settings->aboutus : "";
    return view("content", $this->viewdata);
  }

  function upload_thumbnail()
  {
    helper(['form', 'url']);
    $input = $this->validate([
      'thumbnail' => [
        'uploaded[thumbnail]',
        'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        'max_size[thumbnail,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('thumbnail');
      $img->move('./uploads/churches');
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
