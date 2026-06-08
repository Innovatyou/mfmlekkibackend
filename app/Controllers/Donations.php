<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Donations_model as donationsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Clients_model as clientsmodel;
use Stripe;
//use App\Models\Home_model as homemodel;

class Donations extends BaseController
{
  protected $session;
  protected $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->apitoken = $this->session->get('apitoken');
  }

  public function index()
  {
    //$data['userRecords'] = $this->devotionalsmodel->usersListing();
    return $this->view("donations/listing", $this->viewdata);
  }

  function donationslisting()
  {
    // Datatables Variables
    $donationsmodel = new donationsmodel();
    $draw = intval($_POST['draw']);
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = "";
    if (isset($_POST['search']['value'])) {
      $searchValue = $_POST['search']['value']; // Search value
    }

    $columnName = "";
    if (isset($_POST['columns'][$columnIndex]['data'])) {
      $columnSortOrder = $_POST['columns'][$columnIndex]['data']; // Search value
    }

    $columnSortOrder = "ASC";
    if (isset($_POST['order'][0]['dir'])) {
      $columnSortOrder = $_POST['order'][0]['dir']; // Search value
    }


    $users = $donationsmodel->donationsListing($columnName, $columnSortOrder, $searchValue, $start, $length, $this->apitoken);
    $total = $donationsmodel->get_total_donations($searchValue,  $this->apitoken);
    //var_dump($users); die;
    $dat = array();

    $count = $start + 1;
    foreach ($users as $r) {
      $dat[] = array(
        $count,
        $r->reason,
        $r->email,
        $r->name,
        $r->reference,
        $r->amount,
        $r->method,
        $r->date
      );
      $count++;
    }

    $output = array(
      "draw" => $draw,
      "recordsTotal" => $total,
      "recordsFiltered" => $total,
      "data" => $dat
    );
    echo json_encode($output);
  }

  public function donate($apitoken)
  {
    $settingsmodel = new settingsmodel();
    $this->viewdata['settings'] = $settingsmodel->getSettings($apitoken);
    if ($this->viewdata['settings'] == NULL) {
      return redirect()->to(base_url() . '/');
      return;
    }
    $this->viewdata['apitoken'] = $apitoken;
    //$data['transid'] = $this->generate_string();
    //$data['hash'] = hash( "sha512","C0Dr8m|12345|1000|Shopping|Vinay|vinay@test.com|3sf0jURk");
    return view("donations/donate", $this->viewdata);
  }


  function generate_string($strength = 16)
  {
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
      $random_character = $input[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
  }

  public function savedonation()
  {
    $data = $this->get_data();
    //var_dump($data); die;
    if (!empty($data)) {
      $reason = isset($data->reason) ? filter_var($data->reason, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $method = isset($data->type) ? filter_var($data->type, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $name = isset($data->name) ? filter_var($data->name, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $amount = isset($data->amount) ? filter_var($data->amount, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : 0;
      $reference = isset($data->reference) ? filter_var($data->reference, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";
      $apitoken = isset($data->apitoken) ? filter_var($data->apitoken, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH) : "";

      $pay_ref['email'] = $email;
      $pay_ref['name'] = $name;
      $pay_ref['reason'] = $reason;
      $pay_ref['reference'] = $reference;
      $pay_ref['amount'] = $amount;
      $pay_ref['method'] = $method;
      $pay_ref['apitoken'] = $apitoken;
      $pay_ref['day'] = date('d');
      $pay_ref['month'] = date('m');
      $pay_ref['year'] = date('Y');
      $donationsmodel = new donationsmodel();
      $donationsmodel->recordDonation($pay_ref);
      $this->senddonationemail($apitoken, $email, $name, $amount);
      echo json_encode(array("status" => $donationsmodel->status, "message" => $donationsmodel->message));
      exit;
    } else {
      echo json_encode(array("status" => "error", "message" => "No data found for this transaction"));
    }
  }

  function thank_you()
  {
    $this->viewdata['message'] = "<p>Thank you for your support and for your belief in doing good.<br>We simply couldnt do what we do without amazing people like you.</p>";
    if (isset($_GET['_p'])) {
      $apitoken = $_GET['_p'];
      $settingsmodel = new settingsmodel();
      $settings = $settingsmodel->getSettings($apitoken);
      if ($settings) {
        $this->viewdata['message'] = $settings->thankyou;
      }
    }
    return view("donations/thank_you", $this->viewdata);
  }

  //
  public function createCharge()
  {
    $settingsmodel = new settingsmodel();
    $data = $this->get_data();
    //var_dump($data); die;
    $apitoken = $data->apitoken;
    $settings = $settingsmodel->getSettings($apitoken);;
    $token = $data->token;
    $email = $data->email;
    $name = $data->name;
    $reason = $data->reason;
    $amount = $data->amount;
    try {
      \Stripe\Stripe::setApiKey($settings->stripe_secret);
      $charge = \Stripe\Charge::create(array(
        'amount' => $amount * 100, // Amount in cents!
        'currency' => 'usd',
        'receipt_email' => $email,
        'source' => $token,
        'description' => $name
      ));

      $pay_ref['email'] = $email;
      $pay_ref['name'] = $name;
      $pay_ref['reason'] = $reason;
      $pay_ref['reference'] = $charge->id;
      $pay_ref['amount'] = $amount;
      $pay_ref['method'] = "Stripe";
      $pay_ref['apitoken'] = $apitoken;
      $pay_ref['day'] = date('d');
      $pay_ref['month'] = date('m');
      $pay_ref['year'] = date('Y');
      $donationsmodel = new donationsmodel();
      $donationsmodel->recordDonation($pay_ref);
      $this->senddonationemail($apitoken, $email, $name, $amount);
      echo json_encode(array("status" => $donationsmodel->status, "message" => $donationsmodel->message));
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

  private function senddonationemail($apitoken, $donationemail, $fullname, $amount)
  {
    $clientsmodel = new clientsmodel();
    $church = $clientsmodel->getClientInfoWithApiToken($apitoken);
    $settingsmodel = new settingsmodel();
    $settings = $settingsmodel->getSettings($apitoken);
    $htmlContent = '<p>New Donation Recieved.<br>
      AMOUNT: ' . $amount . '<br>
      SENT BY:  ' . $fullname . '</p>';
    $thankyoumessage = $settings->thankyou == "" ? "<p>Thank you for your support and for your belief in doing good.<br>We simply couldnt do what we do without amazing people like you.</p>" : $settings->thankyou;
    $htmlContent2 = '<p>Your donation of ' . $amount . ' was successful.<br>' . $thankyoumessage;

    $emailconfig = $settingsmodel->getEmailConfig();
    $this->sendEmail("no-reply", $emailconfig, $church->email, "Donation Recieved", $this->getChurchEmailTemplate($church->fullname, $htmlContent));

    $this->sendEmail("no-reply", $emailconfig, $donationemail, "Donation Recieved", $this->getChurchEmailTemplate($fullname, $htmlContent2));
  }
}
