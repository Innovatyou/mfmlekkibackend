<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Messaging_model as messagingmodel;
use App\Models\Lists_model as listsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Members_model as membersmodel;
use App\Models\Manage_model as managemodel;

use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

use ManeOlawale\Termii\Client as Termii;

// Load Composer's autoloader
//require '../vendor/autoload.php';

class Messaging extends BaseController
{
	protected $role = 0;
	protected $apitoken = "";

	public function __construct()
	{
		$session = session();
		$this->apitoken = $session->get('apitoken');
		$this->role = $session->get('role');
	}

	public function index()
	{
		$messagingmodel = new messagingmodel();
		$this->viewdata['messages'] = $messagingmodel->messageListing($this->apitoken);
		return $this->view("messaging/listing", $this->viewdata);
	}

	public function newMessage()
	{
		$listsmodel = new listsmodel();
		$this->viewdata['lists'] = $listsmodel->listsListing($this->apitoken);
		$istwilioenabled = 1;
		$istermiienabled = 1;
		$isemailenabled = 1;

		$settingsmodel = new settingsmodel();
		$settings = $settingsmodel->getSettings($this->apitoken);
		//if any of the settings are available
		if (
			$settings->twilio_account_sid != ""
			&& $settings->twilio_auth_token != ""
			&& $settings->twilio_phonenumber != ""
		) {
			$istwilioenabled = 0;
		}
		if (
			$settings->termi_apikey != ""
			&& $settings->termi_sender_id != ""
		) {
			$istermiienabled = 0;
		}
		//for email 
		$managemodel = new managemodel();
		$managersettings = $managemodel->getManagerSettings();
		if (
			$managersettings->mail_username != ""
			&& $managersettings->mail_password != ""
			&& $managersettings->mail_smtp_host != ""
			&& $managersettings->mail_protocol != ""
			&& $managersettings->mail_port != 0
		) {
			$isemailenabled = 0;
		}

		$this->viewdata['istwilioenabled'] = $istwilioenabled;
		$this->viewdata['istermiienabled'] = $istermiienabled;
		$this->viewdata['isemailenabled'] = $isemailenabled;
		return $this->view("messaging/new", $this->viewdata);
	}

	public function resendMessage($id = 0)
	{
		$messagingmodel = new messagingmodel();
		$this->viewdata['message'] = $messagingmodel->getMessageInfo($id, $this->apitoken);
		if (count((array)$this->viewdata['message']) == 0) {
			return redirect()->to(base_url() . '/messaging');
		}
		$listsmodel = new listsmodel();
		$this->viewdata['lists'] = $listsmodel->listsListing($this->apitoken);
		$istwilioenabled = 1;
		$istermiienabled = 1;
		$isemailenabled = 1;

		$settingsmodel = new settingsmodel();
		$settings = $settingsmodel->getSettings($this->apitoken);
		//if any of the settings are available
		if (
			$settings->twilio_account_sid != ""
			&& $settings->twilio_auth_token != ""
			&& $settings->twilio_phonenumber != ""
		) {
			$istwilioenabled = 0;
		}
		if (
			$settings->termi_apikey != ""
			&& $settings->termi_sender_id != ""
		) {
			$istermiienabled = 0;
		}
		//for email 
		$managemodel = new managemodel();
		$managersettings = $managemodel->getManagerSettings();
		if (
			$managersettings->mail_username != ""
			&& $managersettings->mail_password != ""
			&& $managersettings->mail_smtp_host != ""
			&& $managersettings->mail_protocol != ""
			&& $managersettings->mail_port != 0
		) {
			$isemailenabled = 0;
		}

		$this->viewdata['istwilioenabled'] = $istwilioenabled;
		$this->viewdata['istermiienabled'] = $istermiienabled;
		$this->viewdata['isemailenabled'] = $isemailenabled;
		return $this->view("messaging/edit", $this->viewdata);
	}

	public function sendnewmessage()
	{
		$list = $this->request->getVar('list');
		$title = $this->request->getVar('title');
		$message = $this->request->getVar('message');
		$smsgateway = $this->request->getVar('smsgateway');
		$sms = "NO";
		$email = "NO";
		$app_notification = "NO";
		$formats = $this->request->getVar('formats');
		$draft = 1;
		if ($formats != NULL) {
			$draft = 0;
			foreach ($formats as $val) {
				$itm = (array_values($val)[0]);
				if ($itm == "sms") {
					$sms = "YES";
				}
				if ($itm == "email") {
					$email = "YES";
				}
			}
		}
		//$members = $this->request->getVar('members');
		//var_dump($_POST); die;
		$info = array(
			'apitoken' => $this->apitoken,
			'title' => $title,
			'listid' => $list,
			'message' => $message,
			'sms' => $sms,
			'email' => $email,
			'app_notification' => $app_notification,
			'date' => time(),
		);
		$messagingmodel = new messagingmodel();
		$msg_id = $messagingmodel->addNewMessage($info);

		if ($email == "YES" || $sms == "YES") {
			$membersmodel = new membersmodel();
			$members = [];
			if ($list == 0) {
				$members = $membersmodel->getMembers($this->apitoken);
			} else {
				$members = $membersmodel->getMembersByListid($list, $this->apitoken);
			}

			$settingsmodel = new settingsmodel();
			$adminsettings = $settingsmodel->getSettings($this->apitoken);
			//send email
			if ($email == "YES") {
				$emailconfig = $settingsmodel->getEmailConfig();
				$branchname = $adminsettings->churchname;
				foreach ($members as $res) {
					if ($res->email != "") {
						$this->sendEmail($branchname, $emailconfig, $res->email, $title, $message);
					}
				}
			}
			//send sms
			if ($sms == "YES") {
				$smsconfig = $settingsmodel->getSMSConfig($adminsettings, $smsgateway, $this->apitoken);
				foreach ($members as $res) {
					if ($res->phonenumber != "") {
						$this->sendSMS($smsgateway, $smsconfig, $res->phonenumber, $message);
					}
				}
			}
		}
		$session = session();
		$session->setFlashdata('success', $draft == 0 ? "Message sent successfully." : "Message saved as draft.");
		return redirect()->to(base_url() . '/messaging');
	}

	function editMessageData()
	{
		$id = $this->request->getVar('id');
		$title = $this->request->getVar('title');
		$message = $this->request->getVar('message');
		//$members = $this->request->getVar('members');
		//var_dump($_POST); die;
		$info = array(
			'title' => $title,
			'message' => $message,
		);
		$messagingmodel = new messagingmodel();
		$messagingmodel->editMessage($info, $id, $this->apitoken);
		$session = session();
		$session->setFlashdata('success', "Message updated successfully.");
		return redirect()->to(base_url() . '/editMessage/' . $id);
	}

	function deleteMessage($id = 0)
	{
		$messagingmodel = new messagingmodel();
		$messagingmodel->deleteMessage($id, $this->apitoken);
		$session = session();
		if ($messagingmodel->status == "ok") {
			$session->setFlashdata('success', $messagingmodel->message);
		} else {
			$session->setFlashdata('error', $messagingmodel->message);
		}
		return redirect()->to(base_url() . '/messaging');
	}

	private function sendSMS($smsgateway, $smsconfig, $phonenumber, $content)
	{
		//var_dump($smsgateway); die;
		if ($smsgateway == "twilio") {
			try {
				$twilio = new Client($smsgateway->twilio_account_sid, $smsgateway->twilio_auth_token);
				$twiliomsg = $twilio->messages
					->create(
						$phonenumber, // to
						["from" => $smsgateway->twilio_phonenumber, "body" => $content]
					);
			} catch (\Exception $e) {
				//die( $e->getCode() . ' : ' . $e->getMessage() );
			}
		}
		if ($smsgateway == "termii") {
			try {
				$client = new Termii(
					$smsconfig->termi_apikey,
					['sender_id' => $smsconfig->termi_sender_id, 'channel' => 'generic',]
				);
				$client->sms->send($phonenumber, $content);
			} catch (\Exception $e) {
				//die( $e->getCode() . ' : ' . $e->getMessage() );
			}
		}
	}
}
