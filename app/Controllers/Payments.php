<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Settings_model as settingsmodel;
use App\Models\Manage_model as managemodel;
use Stripe;

class Payments extends BaseController
{
  public function makepayment()
  {
    $session = session();
    $settingsmodel = new settingsmodel();
    $this->viewdata['church'] = $settingsmodel->getChurchProfile();
    $managemodel = new managemodel();
    $this->viewdata['settings'] = $managemodel->getManagerSettings();
    return view('churches/payments', $this->viewdata);
  }

  public function createCharge()
  {
    $session = session();
    $settingsmodel = new settingsmodel();
    $church = $settingsmodel->getChurchProfile();
    $managemodel = new managemodel();
    $settings = $managemodel->getManagerSettings();
    $data = $this->get_data();
    //var_dump($data); die;
    $token = $data->token;
    $duration = $data->duration;
    $amount = $settings->subscription_usd_amount * $duration;
    try {
      \Stripe\Stripe::setApiKey(getenv('stripe.secret'));
      $charge = \Stripe\Charge::create(array(
        'amount' => $amount * 100, // Amount in cents!
        'currency' => 'usd',
        'receipt_email' => $church->email,
        'source' => $token,
        'description' => $church->fullname
      ));
      $subscribe_date = $church->substartdate;
      $expiry_date = date('Y-m-d H:i:s', strtotime($church->substartdate . ' +' . $duration . ' month'));

      $pay_ref['email'] = $church->email;
      $pay_ref['name'] = $church->fullname;
      $pay_ref['paymentfor'] = $duration . " month(s) subscription";
      $pay_ref['reference'] = $charge->id;
      $pay_ref['amount'] = $amount;
      $pay_ref['gateway'] = "stripe";
      $pay_ref['day'] = date('d');
      $pay_ref['month'] = date('m');
      $pay_ref['year'] = date('Y');
      $managemodel->recordTransaction($pay_ref);
      //update church
      $sub['status'] = 0;
      $sub['subscribe_date'] = $subscribe_date;
      $sub['expiry_date'] = $expiry_date;
      $settingsmodel->editchurchprofile($sub);
      $this->updatechurchstatus();
      echo json_encode(array("status" => "ok", "message" => "Thanks for subscribing, your subscription will expire on\r\n " . $expiry_date));
    } catch (\Stripe\Error\ApiConnection $e) {
      // Network problem, perhaps try again.
      $e_json = $e->getJsonBody();
      $error = $e_json['error'];
      //return redirect()->back()->with('error', $error);
      echo json_encode(array("status" => "error", "message" => $error));
    } catch (\Stripe\Error\InvalidRequest $e) {
      $e_json = $e->getJsonBody();
      $error = $e_json['error'];
      //return redirect()->back()->with('error', $error);
      echo json_encode(array("status" => "error", "message" => $error));
    } catch (\Stripe\Error\Api $e) {
      // Stripe's servers are down!
      $e_json = $e->getJsonBody();
      $error = $e_json['error'];
      //return redirect()->back()->with('error', $error);
      echo json_encode(array("status" => "error", "message" => $error));
    } catch (\Stripe\Error\Card $e) {
      // Card was declined.
      $e_json = $e->getJsonBody();
      $error = $e_json['error'];
      //return redirect()->back()->with('error', $error);
      echo json_encode(array("status" => "error", "message" => $error));
    }
    exit;
  }

  public function savesubscription()
  {
    $session = session();
    $settingsmodel = new settingsmodel();
    $church = $settingsmodel->getChurchProfile();
    $managemodel = new managemodel();
    $settings = $managemodel->getManagerSettings();
    $data = $this->get_data();
    $duration = isset($data->duration) ? filter_var($data->duration, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
    $selected = isset($data->selected) ? filter_var($data->selected, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $reference = isset($data->reference) ? filter_var($data->reference, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
    $type = isset($data->type) ? filter_var($data->type, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

    $amount = $settings->subscription_usd_amount * $duration;
    if ($selected == "local") {
      $amount = $settings->subscription_local_amount * $duration;
    }
    $subscribe_date = $church->substartdate;
    $expiry_date = date('Y-m-d H:i:s', strtotime($church->substartdate . ' +' . $duration . ' month'));

    $pay_ref['email'] = $church->email;
    $pay_ref['name'] = $church->fullname;
    $pay_ref['paymentfor'] = $duration . " month(s) subscription";
    $pay_ref['reference'] = $reference;
    $pay_ref['amount'] = $amount;
    $pay_ref['gateway'] = $type;
    $pay_ref['day'] = date('d');
    $pay_ref['month'] = date('m');
    $pay_ref['year'] = date('Y');
    $managemodel->recordTransaction($pay_ref);
    //update church
    $sub['status'] = 0;
    $sub['subscribe_date'] = $subscribe_date;
    $sub['expiry_date'] = $expiry_date;
    $settingsmodel->editchurchprofile($sub);
    $this->updatechurchstatus();
    echo json_encode(array("status" => "ok", "message" => "Thanks for subscribing, your subscription will expire on\r\n " . $expiry_date));
    exit;
  }

  private function updatechurchstatus()
  {
    $session = session();
    $session->set('status', 0);
  }
}
