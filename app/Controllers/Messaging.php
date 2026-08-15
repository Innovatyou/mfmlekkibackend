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

	public function __construct()
	{
		$session = session();
		$this->role = $session->get('role');
	}

	public function index()
	{
		$messagingmodel = new messagingmodel();
		$this->viewdata['messages'] = $messagingmodel->messageListing();
		return $this->view("messaging/listing", $this->viewdata);
	}

	public function newMessage()
	{
		$listsmodel = new listsmodel();
		$this->viewdata['lists'] = $listsmodel->listsListing();
		$istwilioenabled = 1;
		$istermiienabled = 1;
		$isemailenabled = 1;

		$settingsmodel = new settingsmodel();
		$settings = $settingsmodel->getSettings();
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
		$this->viewdata['message'] = $messagingmodel->getMessageInfo($id);
		if (count((array)$this->viewdata['message']) == 0) {
			return redirect()->to(base_url() . '/messaging');
		}
		$listsmodel = new listsmodel();
		$this->viewdata['lists'] = $listsmodel->listsListing();
		$istwilioenabled = 1;
		$istermiienabled = 1;
		$isemailenabled = 1;

		$settingsmodel = new settingsmodel();
		$settings = $settingsmodel->getSettings();
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
				$members = $membersmodel->getMembers();
			} else {
				$members = $membersmodel->getMembersByListid($list);
			}

			$settingsmodel = new settingsmodel();
			$adminsettings = $settingsmodel->getSettings();
			//send email
			if ($email == "YES") {
				$emailconfig = $settingsmodel->getEmailConfig();
				$branchname = $adminsettings->churchname;
				$htmlMessage = $this->buildEmailTemplate($branchname, $title, $message);
				foreach ($members as $res) {
					if ($res->email != "") {
						$this->sendEmail($branchname, $emailconfig, $res->email, $title, $htmlMessage);
					}
				}
			}
			//send sms
			if ($sms == "YES") {
				$smsconfig = $settingsmodel->getSMSConfig($adminsettings, $smsgateway);
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
		$messagingmodel->editMessage($info, $id);
		$session = session();
		$session->setFlashdata('success', "Message updated successfully.");
		return redirect()->to(base_url() . '/editMessage/' . $id);
	}

	function deleteMessage($id = 0)
	{
		$messagingmodel = new messagingmodel();
		$messagingmodel->deleteMessage($id);
		$session = session();
		if ($messagingmodel->status == "ok") {
			$session->setFlashdata('success', $messagingmodel->message);
		} else {
			$session->setFlashdata('error', $messagingmodel->message);
		}
		return redirect()->to(base_url() . '/messaging');
	}

	private function buildEmailTemplate(string $churchName, string $subject, string $body): string
	{
		$year = date('Y');
		$safeName    = htmlspecialchars($churchName, ENT_QUOTES, 'UTF-8');
		$safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
		return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>{$safeSubject}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f2f5;padding:40px 20px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        <!-- Header -->
        <tr>
          <td align="center" style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);border-radius:12px 12px 0 0;padding:32px 40px;">
            <div style="display:inline-block;width:48px;height:48px;background:rgba(255,255,255,.18);border-radius:12px;vertical-align:middle;margin-bottom:14px;line-height:48px;text-align:center;">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9,22 9,12 15,12 15,22"/>
              </svg>
            </div>
            <div style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-.02em;">{$safeName}</div>
          </td>
        </tr>

        <!-- Subject bar -->
        <tr>
          <td style="background:#ffffff;padding:28px 40px 0;">
            <h2 style="margin:0 0 16px;font-size:20px;font-weight:700;color:#0f172a;border-bottom:1px solid #e2e8f0;padding-bottom:16px;">{$safeSubject}</h2>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="background:#ffffff;padding:20px 40px 36px;">
            <div style="font-size:15px;color:#374151;line-height:1.75;">
              {$body}
            </div>
          </td>
        </tr>

        <!-- Divider -->
        <tr>
          <td style="background:#ffffff;padding:0 40px;">
            <hr style="border:none;border-top:1px solid #e2e8f0;margin:0;">
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f8fafc;border-radius:0 0 12px 12px;padding:22px 40px;text-align:center;">
            <p style="margin:0 0 6px;font-size:13px;color:#94a3b8;">
              &copy; {$year} {$safeName}. This message was sent to you as a member of our community.
            </p>
            <p style="margin:0;font-size:12px;color:#cbd5e1;">
              If you have questions, please contact your church office directly.
            </p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
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
