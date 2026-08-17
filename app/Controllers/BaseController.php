<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Verify_model as verifymodel;
use App\Models\Socials_model as socialsmodel;
use App\Models\Fcm_model as fcmmodel;
use App\Models\Settings_model as settingsmodel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var IncomingRequest|CLIRequest
	 */
	protected $request;
	protected $viewdata = [];

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['AdminAuth'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		// CORS: browser clients (Flutter web build, admin/landing frontends) need
		// these headers on every response. Set via raw header() — not the CI4
		// Response object — because most API actions below finish with a direct
		// echo+exit that bypasses the framework's response/filter pipeline.
		$origin = $request->getHeaderLine('Origin') ?: '*';
		header('Access-Control-Allow-Origin: ' . $origin);
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');
		header('Access-Control-Max-Age: 86400');
		header('Vary: Origin');

		//echo $date = date('m/d/Y h:i:s a', time()); die;
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
		$path = "Language/en.php";
		$this->viewdata['locale'] = require APPPATH . $path;
	}



	protected function jsonOut(array $data): void
	{
		header('Content-Type: application/json');
		echo json_encode($data);
		exit;
	}

	public function check_notify_user($itm_id, $type, $user, $email)
	{
		if ($user == $email) return;
		$socialsmodel = new socialsmodel();
		$settings = $socialsmodel->fetch_user_settings($user);
		$user_data = $socialsmodel->getUpdatedUserProfile($email);
		$msg = "New Notification";
		if ($type == "follow") {
			$msg = $user_data->name . " started following you";
			$socialsmodel->saveNotificationData($itm_id, $type, $email, $user);
			if ($settings->notify_follows == 0) {
				$this->notify_user($user, $user_data->photo, $msg);
			}
		} else if ($type == "comment") {
			$msg = $user_data->name . " commented on your post";
			$socialsmodel->saveNotificationData($itm_id, $type, $email, $user);
			if ($settings->notify_comments == 0) {
				$this->notify_user($user, $user_data->photo, $msg);
			}
		} else if ($type == "like") {
			$msg = $user_data->name . " liked your post";
			$socialsmodel->saveNotificationData($itm_id, $type, $email, $user);
			if ($settings->notify_likes == 0) {
				$this->notify_user($user, $user_data->photo, $msg);
			}
		}
	}

	public function notify_user($email, $avatar, $msg)
	{
		$settingsmodel = new settingsmodel();
		$API_SERVER_KEY = $settingsmodel->getFcmServerKey();
		$fcmmodel = new fcmmodel();
		$fcmmodel->userActionsNotification($API_SERVER_KEY, $email, $avatar, $msg);
	}

	public function get_data()
	{
		$data = [];
		if (isset($_POST['data'])) {
			$data = json_decode($_POST['data']);
		} else {
			$rawBody = file_get_contents('php://input');
			if (!empty($rawBody)) {
				$decoded = json_decode($rawBody, TRUE);
				if (is_array($decoded) && isset($decoded['data'])) {
					$data = (object) $decoded['data'];
				} elseif (is_array($decoded)) {
					$data = (object) $decoded;
				}
			}
		}
		return $data;
	}

	public function cleanup($data)
	{
		$data = $this->security->xss_clean($data);
		$data = trim($data);
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		return $data;
	}

	//function to process link for both email activation and password reset
	public function getVerificationLink($email)
	{
		$verifymodel = new verifymodel();
		$data = array('email' => $email, 'activation_id' => $this->generate_string(), 'agent' => $_SERVER['HTTP_USER_AGENT'], 'client_ip' => $_SERVER['REMOTE_ADDR']);
		$verifymodel->insertData($data);
		return $this->getBaseUrl() . "verifyEmailLink/" . $data['activation_id'];
	}

	public function getPasswordResetLink($email)
	{
		$verifymodel = new verifymodel();
		$data = array('email' => $email, 'activation_id' => $this->generate_string(), 'agent' => $_SERVER['HTTP_USER_AGENT'], 'client_ip' => $_SERVER['REMOTE_ADDR']);
		$verifymodel->insertData($data);
		return $this->getBaseUrl() . "resetLink/" . $data['activation_id'];
	}


	//function to generate random string
	private function generate_string($strength = 30)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$input_length = strlen($chars);
		$random_string = '';
		for ($i = 0; $i < $strength; $i++) {
			$random_character = $chars[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}
		return $random_string . "_" . time();
	}

	//function to return base url
	public function getBaseUrl()
	{
		$base  = "https://" . $_SERVER['HTTP_HOST'];
		return $base .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
	}

	public function sendEmail($branchname, $emailconfig, $reciever, $subject, $content)
	{
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);
		// Prepare common mail settings
		$mail->SMTPDebug = 0; // 0 - Disable Debugging, 2 - Responses received from the server
		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->SMTPAuth = true; // Enable SMTP authentication
		$mail->Username = $emailconfig->mail_username; // SMTP username
		$mail->Password = $emailconfig->mail_password; // SMTP password
		$mail->setFrom($emailconfig->mail_username, $emailconfig->mail_username);
		$mail->addAddress($reciever); // Add a recipient
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body = $content;
		$mail->AltBody = strip_tags($content); // Plain text fallback for non-HTML mail clients
		$mail->Timeout = 5; // seconds — an unreachable/slow SMTP host must fail fast, not hang the request

		// Attempt 1: use configured host/port/protocol
		try {
			$mail->Host = $emailconfig->mail_smtp_host;
			$mail->SMTPSecure = $emailconfig->mail_protocol;
			$mail->Port = $emailconfig->mail_port;
			$mail->send();
			return true;
		} catch (Exception $e) {
			log_message('warning', "PHPMailer first attempt failed: {$mail->ErrorInfo}; Exception: " . $e->getMessage());
		}

		// Attempt 2: common fallback — implicit SSL on port 465
		try {
			$mail->Host = $emailconfig->mail_smtp_host;
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			$mail->send();
			log_message('info', 'PHPMailer fallback to ssl:465 succeeded');
			return true;
		} catch (Exception $e) {
			log_message('error', "PHPMailer fallback failed: {$mail->ErrorInfo}; Exception: " . $e->getMessage());
			return false;
		}
	}

	//here define which template to use
	protected function view($page, $data = [])
	{
		// Automatically merge viewdata (which contains locale and other common variables)
		$mergedData = array_merge($this->viewdata, $data);
		
		// Render the three view parts and concatenate
		$header = view('templates/header', $mergedData);
		$content = view($page, $mergedData);
		$footer = view('templates/footer', $mergedData);
		
		return $header . $content . $footer;
	}

	//here define which template to use
	protected function managerview($page, $data = [])
	{
		return view('templates/manageheader', $data)
			. view($page, $data)
			. view('templates/managefooter', $data);
	}

	public function getActivationEmailTemplate($link, $linktext, $content)
	{
		return <<<EOD
		<!doctype html>
		<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title></title>
			<style>
				/* -------------------------------------
						GLOBAL RESETS
				------------------------------------- */

				/*All the styling goes here*/

				img {
					border: none;
					-ms-interpolation-mode: bicubic;
					max-width: 100%;
				}

				body {
					background-color: #f6f6f6;
					font-family: sans-serif;
					-webkit-font-smoothing: antialiased;
					font-size: 14px;
					line-height: 1.4;
					margin: 0;
					padding: 0;
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%;
				}

				table {
					border-collapse: separate;
					mso-table-lspace: 0pt;
					mso-table-rspace: 0pt;
					width: 100%; }
					table td {
						font-family: sans-serif;
						font-size: 14px;
						vertical-align: top;
				}

				/* -------------------------------------
						BODY & CONTAINER
				------------------------------------- */

				.body {
					background-color: #f6f6f6;
					width: 100%;
				}

				/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
				.container {
					display: block;
					margin: 0 auto !important;
					/* makes it centered */
					max-width: 580px;
					padding: 10px;
					width: 580px;
				}

				/* This should also be a block element, so that it will fill 100% of the .container */
				.content {
					box-sizing: border-box;
					display: block;
					margin: 0 auto;
					max-width: 580px;
					padding: 10px;
				}

				/* -------------------------------------
						HEADER, FOOTER, MAIN
				------------------------------------- */
				.main {
					background: #ffffff;
					border-radius: 3px;
					width: 100%;
				}

				.wrapper {
					box-sizing: border-box;
					padding: 20px;
				}

				.content-block {
					padding-bottom: 10px;
					padding-top: 10px;
				}

				.footer {
					clear: both;
					margin-top: 10px;
					text-align: center;
					width: 100%;
				}
					.footer td,
					.footer p,
					.footer span,
					.footer a {
						color: #999999;
						font-size: 12px;
						text-align: center;
				}

				/* -------------------------------------
						TYPOGRAPHY
				------------------------------------- */
				h1,
				h2,
				h3,
				h4 {
					color: #000000;
					font-family: sans-serif;
					font-weight: 400;
					line-height: 1.4;
					margin: 0;
					margin-bottom: 30px;
				}

				h1 {
					font-size: 35px;
					font-weight: 300;
					text-align: center;
					text-transform: capitalize;
				}

				p,
				ul,
				ol {
					font-family: sans-serif;
					font-size: 14px;
					font-weight: normal;
					margin: 0;
					margin-bottom: 15px;
				}
					p li,
					ul li,
					ol li {
						list-style-position: inside;
						margin-left: 5px;
				}

				a {
					color: #3498db;
					text-decoration: underline;
				}

				/* -------------------------------------
						BUTTONS
				------------------------------------- */
				.btn {
					box-sizing: border-box;
					width: 100%; }
					.btn > tbody > tr > td {
						padding-bottom: 15px; }
					.btn table {
						width: auto;
				}
					.btn table td {
						background-color: #ffffff;
						border-radius: 5px;
						text-align: center;
				}
					.btn a {
						background-color: #ffffff;
						border: solid 1px #3498db;
						border-radius: 5px;
						box-sizing: border-box;
						color: #3498db;
						cursor: pointer;
						display: inline-block;
						font-size: 14px;
						font-weight: bold;
						margin: 0;
						padding: 12px 25px;
						text-decoration: none;
						text-transform: capitalize;
				}

				.btn-primary table td {
					background-color: #3498db;
				}

				.btn-primary a {
					background-color: #3498db;
					border-color: #3498db;
					color: #ffffff;
				}

				/* -------------------------------------
						OTHER STYLES THAT MIGHT BE USEFUL
				------------------------------------- */
				.last {
					margin-bottom: 0;
				}

				.first {
					margin-top: 0;
				}

				.align-center {
					text-align: center;
				}

				.align-right {
					text-align: right;
				}

				.align-left {
					text-align: left;
				}

				.clear {
					clear: both;
				}

				.mt0 {
					margin-top: 0;
				}

				.mb0 {
					margin-bottom: 0;
				}

				.preheader {
					color: transparent;
					display: none;
					height: 0;
					max-height: 0;
					max-width: 0;
					opacity: 0;
					overflow: hidden;
					mso-hide: all;
					visibility: hidden;
					width: 0;
				}

				.powered-by a {
					text-decoration: none;
				}

				hr {
					border: 0;
					border-bottom: 1px solid #f6f6f6;
					margin: 20px 0;
				}

				/* -------------------------------------
						RESPONSIVE AND MOBILE FRIENDLY STYLES
				------------------------------------- */
				@media only screen and (max-width: 620px) {
					table.body h1 {
						font-size: 28px !important;
						margin-bottom: 10px !important;
					}
					table.body p,
					table.body ul,
					table.body ol,
					table.body td,
					table.body span,
					table.body a {
						font-size: 16px !important;
					}
					table.body .wrapper,
					table.body .article {
						padding: 10px !important;
					}
					table.body .content {
						padding: 0 !important;
					}
					table.body .container {
						padding: 0 !important;
						width: 100% !important;
					}
					table.body .main {
						border-left-width: 0 !important;
						border-radius: 0 !important;
						border-right-width: 0 !important;
					}
					table.body .btn table {
						width: 100% !important;
					}
					table.body .btn a {
						width: 100% !important;
					}
					table.body .img-responsive {
						height: auto !important;
						max-width: 100% !important;
						width: auto !important;
					}
				}

				/* -------------------------------------
						PRESERVE THESE STYLES IN THE HEAD
				------------------------------------- */
				@media all {
					.ExternalClass {
						width: 100%;
					}
					.ExternalClass,
					.ExternalClass p,
					.ExternalClass span,
					.ExternalClass font,
					.ExternalClass td,
					.ExternalClass div {
						line-height: 100%;
					}
					.apple-link a {
						color: inherit !important;
						font-family: inherit !important;
						font-size: inherit !important;
						font-weight: inherit !important;
						line-height: inherit !important;
						text-decoration: none !important;
					}
					#MessageViewBody a {
						color: inherit;
						text-decoration: none;
						font-size: inherit;
						font-family: inherit;
						font-weight: inherit;
						line-height: inherit;
					}
					.btn-primary table td:hover {
						background-color: #34495e !important;
					}
					.btn-primary a:hover {
						background-color: #34495e !important;
						border-color: #34495e !important;
					}
				}

			</style>
		</head>
		<body>
			<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
				<tr>
					<td>&nbsp;</td>
					<td class="container">
						<div class="content">

							<!-- START CENTERED WHITE CONTAINER -->
							<table role="presentation" class="main">

								<!-- START MAIN CONTENT AREA -->
								<tr>
									<td class="wrapper">
										<table role="presentation" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td>
													<p>Hi,</p>
													$content
													<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
														<tbody>
															<tr>
																<td align="left">
																	<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td> <a href="$link" target="_blank">$linktext</a> </td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>

							<!-- END MAIN CONTENT AREA -->
							</table>
							<!-- END CENTERED WHITE CONTAINER -->



						</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</body>
		</html>
		EOD;
	}

	public function getChurchEmailTemplate($churchname, $content)
	{
		return <<<EOD
		<!doctype html>
		<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title></title>
			<style>
				/* -------------------------------------
						GLOBAL RESETS
				------------------------------------- */

				/*All the styling goes here*/

				img {
					border: none;
					-ms-interpolation-mode: bicubic;
					max-width: 100%;
				}

				body {
					background-color: #f6f6f6;
					font-family: sans-serif;
					-webkit-font-smoothing: antialiased;
					font-size: 14px;
					line-height: 1.4;
					margin: 0;
					padding: 0;
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%;
				}

				table {
					border-collapse: separate;
					mso-table-lspace: 0pt;
					mso-table-rspace: 0pt;
					width: 100%; }
					table td {
						font-family: sans-serif;
						font-size: 14px;
						vertical-align: top;
				}

				/* -------------------------------------
						BODY & CONTAINER
				------------------------------------- */

				.body {
					background-color: #f6f6f6;
					width: 100%;
				}

				/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
				.container {
					display: block;
					margin: 0 auto !important;
					/* makes it centered */
					max-width: 580px;
					padding: 10px;
					width: 580px;
				}

				/* This should also be a block element, so that it will fill 100% of the .container */
				.content {
					box-sizing: border-box;
					display: block;
					margin: 0 auto;
					max-width: 580px;
					padding: 10px;
				}

				/* -------------------------------------
						HEADER, FOOTER, MAIN
				------------------------------------- */
				.main {
					background: #ffffff;
					border-radius: 3px;
					width: 100%;
				}

				.wrapper {
					box-sizing: border-box;
					padding: 20px;
				}

				.content-block {
					padding-bottom: 10px;
					padding-top: 10px;
				}

				.footer {
					clear: both;
					margin-top: 10px;
					text-align: center;
					width: 100%;
				}
					.footer td,
					.footer p,
					.footer span,
					.footer a {
						color: #999999;
						font-size: 12px;
						text-align: center;
				}

				/* -------------------------------------
						TYPOGRAPHY
				------------------------------------- */
				h1,
				h2,
				h3,
				h4 {
					color: #000000;
					font-family: sans-serif;
					font-weight: 400;
					line-height: 1.4;
					margin: 0;
					margin-bottom: 30px;
				}

				h1 {
					font-size: 35px;
					font-weight: 300;
					text-align: center;
					text-transform: capitalize;
				}

				p,
				ul,
				ol {
					font-family: sans-serif;
					font-size: 14px;
					font-weight: normal;
					margin: 0;
					margin-bottom: 15px;
				}
					p li,
					ul li,
					ol li {
						list-style-position: inside;
						margin-left: 5px;
				}

				a {
					color: #3498db;
					text-decoration: underline;
				}

				/* -------------------------------------
						BUTTONS
				------------------------------------- */
				.btn {
					box-sizing: border-box;
					width: 100%; }
					.btn > tbody > tr > td {
						padding-bottom: 15px; }
					.btn table {
						width: auto;
				}
					.btn table td {
						background-color: #ffffff;
						border-radius: 5px;
						text-align: center;
				}
					.btn a {
						background-color: #ffffff;
						border: solid 1px #3498db;
						border-radius: 5px;
						box-sizing: border-box;
						color: #3498db;
						cursor: pointer;
						display: inline-block;
						font-size: 14px;
						font-weight: bold;
						margin: 0;
						padding: 12px 25px;
						text-decoration: none;
						text-transform: capitalize;
				}

				.btn-primary table td {
					background-color: #3498db;
				}

				.btn-primary a {
					background-color: #3498db;
					border-color: #3498db;
					color: #ffffff;
				}

				/* -------------------------------------
						OTHER STYLES THAT MIGHT BE USEFUL
				------------------------------------- */
				.last {
					margin-bottom: 0;
				}

				.first {
					margin-top: 0;
				}

				.align-center {
					text-align: center;
				}

				.align-right {
					text-align: right;
				}

				.align-left {
					text-align: left;
				}

				.clear {
					clear: both;
				}

				.mt0 {
					margin-top: 0;
				}

				.mb0 {
					margin-bottom: 0;
				}

				.preheader {
					color: transparent;
					display: none;
					height: 0;
					max-height: 0;
					max-width: 0;
					opacity: 0;
					overflow: hidden;
					mso-hide: all;
					visibility: hidden;
					width: 0;
				}

				.powered-by a {
					text-decoration: none;
				}

				hr {
					border: 0;
					border-bottom: 1px solid #f6f6f6;
					margin: 20px 0;
				}

				/* -------------------------------------
						RESPONSIVE AND MOBILE FRIENDLY STYLES
				------------------------------------- */
				@media only screen and (max-width: 620px) {
					table.body h1 {
						font-size: 28px !important;
						margin-bottom: 10px !important;
					}
					table.body p,
					table.body ul,
					table.body ol,
					table.body td,
					table.body span,
					table.body a {
						font-size: 16px !important;
					}
					table.body .wrapper,
					table.body .article {
						padding: 10px !important;
					}
					table.body .content {
						padding: 0 !important;
					}
					table.body .container {
						padding: 0 !important;
						width: 100% !important;
					}
					table.body .main {
						border-left-width: 0 !important;
						border-radius: 0 !important;
						border-right-width: 0 !important;
					}
					table.body .btn table {
						width: 100% !important;
					}
					table.body .btn a {
						width: 100% !important;
					}
					table.body .img-responsive {
						height: auto !important;
						max-width: 100% !important;
						width: auto !important;
					}
				}

				/* -------------------------------------
						PRESERVE THESE STYLES IN THE HEAD
				------------------------------------- */
				@media all {
					.ExternalClass {
						width: 100%;
					}
					.ExternalClass,
					.ExternalClass p,
					.ExternalClass span,
					.ExternalClass font,
					.ExternalClass td,
					.ExternalClass div {
						line-height: 100%;
					}
					.apple-link a {
						color: inherit !important;
						font-family: inherit !important;
						font-size: inherit !important;
						font-weight: inherit !important;
						line-height: inherit !important;
						text-decoration: none !important;
					}
					#MessageViewBody a {
						color: inherit;
						text-decoration: none;
						font-size: inherit;
						font-family: inherit;
						font-weight: inherit;
						line-height: inherit;
					}
					.btn-primary table td:hover {
						background-color: #34495e !important;
					}
					.btn-primary a:hover {
						background-color: #34495e !important;
						border-color: #34495e !important;
					}
				}

			</style>
		</head>
		<body>
			<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
				<tr>
					<td>&nbsp;</td>
					<td class="container">
						<div class="content">

							<!-- START CENTERED WHITE CONTAINER -->
							<table role="presentation" class="main">

								<!-- START MAIN CONTENT AREA -->
								<tr>
									<td class="wrapper">
										<table role="presentation" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td>
													<p>Hi $churchname,</p>
													$content
												</td>
											</tr>
										</table>
									</td>
								</tr>

							<!-- END MAIN CONTENT AREA -->
							</table>
							<!-- END CENTERED WHITE CONTAINER -->



						</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</body>
		</html>
		EOD;
	}

	public function getNewClientTemplate($name, $link, $content)
	{
		return <<<EOD
		<!doctype html>
		<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>Envision Apps</title>
			<style>
				/* -------------------------------------
						GLOBAL RESETS
				------------------------------------- */

				/*All the styling goes here*/

				img {
					border: none;
					-ms-interpolation-mode: bicubic;
					max-width: 100%;
				}

				body {
					background-color: #f6f6f6;
					font-family: sans-serif;
					-webkit-font-smoothing: antialiased;
					font-size: 14px;
					line-height: 1.4;
					margin: 0;
					padding: 0;
					-ms-text-size-adjust: 100%;
					-webkit-text-size-adjust: 100%;
				}

				table {
					border-collapse: separate;
					mso-table-lspace: 0pt;
					mso-table-rspace: 0pt;
					width: 100%; }
					table td {
						font-family: sans-serif;
						font-size: 14px;
						vertical-align: top;
				}

				/* -------------------------------------
						BODY & CONTAINER
				------------------------------------- */

				.body {
					background-color: #f6f6f6;
					width: 100%;
				}

				/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
				.container {
					display: block;
					margin: 0 auto !important;
					/* makes it centered */
					max-width: 580px;
					padding: 10px;
					width: 580px;
				}

				/* This should also be a block element, so that it will fill 100% of the .container */
				.content {
					box-sizing: border-box;
					display: block;
					margin: 0 auto;
					max-width: 580px;
					padding: 10px;
				}

				/* -------------------------------------
						HEADER, FOOTER, MAIN
				------------------------------------- */
				.main {
					background: #ffffff;
					border-radius: 3px;
					width: 100%;
				}

				.wrapper {
					box-sizing: border-box;
					padding: 20px;
				}

				.content-block {
					padding-bottom: 10px;
					padding-top: 10px;
				}

				.footer {
					clear: both;
					margin-top: 10px;
					text-align: center;
					width: 100%;
				}
					.footer td,
					.footer p,
					.footer span,
					.footer a {
						color: #999999;
						font-size: 12px;
						text-align: center;
				}

				/* -------------------------------------
						TYPOGRAPHY
				------------------------------------- */
				h1,
				h2,
				h3,
				h4 {
					color: #000000;
					font-family: sans-serif;
					font-weight: 400;
					line-height: 1.4;
					margin: 0;
					margin-bottom: 30px;
				}

				h1 {
					font-size: 35px;
					font-weight: 300;
					text-align: center;
					text-transform: capitalize;
				}

				p,
				ul,
				ol {
					font-family: sans-serif;
					font-size: 14px;
					font-weight: normal;
					margin: 0;
					margin-bottom: 15px;
				}
					p li,
					ul li,
					ol li {
						list-style-position: inside;
						margin-left: 5px;
				}

				a {
					color: #3498db;
					text-decoration: underline;
				}

				/* -------------------------------------
						BUTTONS
				------------------------------------- */
				.btn {
					box-sizing: border-box;
					width: 100%; }
					.btn > tbody > tr > td {
						padding-bottom: 15px; }
					.btn table {
						width: auto;
				}
					.btn table td {
						background-color: #ffffff;
						border-radius: 5px;
						text-align: center;
				}
					.btn a {
						background-color: #ffffff;
						border: solid 1px #3498db;
						border-radius: 5px;
						box-sizing: border-box;
						color: #3498db;
						cursor: pointer;
						display: inline-block;
						font-size: 14px;
						font-weight: bold;
						margin: 0;
						padding: 12px 25px;
						text-decoration: none;
						text-transform: capitalize;
				}

				.btn-primary table td {
					background-color: #3498db;
				}

				.btn-primary a {
					background-color: #3498db;
					border-color: #3498db;
					color: #ffffff;
				}

				/* -------------------------------------
						OTHER STYLES THAT MIGHT BE USEFUL
				------------------------------------- */
				.last {
					margin-bottom: 0;
				}

				.first {
					margin-top: 0;
				}

				.align-center {
					text-align: center;
				}

				.align-right {
					text-align: right;
				}

				.align-left {
					text-align: left;
				}

				.clear {
					clear: both;
				}

				.mt0 {
					margin-top: 0;
				}

				.mb0 {
					margin-bottom: 0;
				}

				.preheader {
					color: transparent;
					display: none;
					height: 0;
					max-height: 0;
					max-width: 0;
					opacity: 0;
					overflow: hidden;
					mso-hide: all;
					visibility: hidden;
					width: 0;
				}

				.powered-by a {
					text-decoration: none;
				}

				hr {
					border: 0;
					border-bottom: 1px solid #f6f6f6;
					margin: 20px 0;
				}

				/* -------------------------------------
						RESPONSIVE AND MOBILE FRIENDLY STYLES
				------------------------------------- */
				@media only screen and (max-width: 620px) {
					table.body h1 {
						font-size: 28px !important;
						margin-bottom: 10px !important;
					}
					table.body p,
					table.body ul,
					table.body ol,
					table.body td,
					table.body span,
					table.body a {
						font-size: 16px !important;
					}
					table.body .wrapper,
					table.body .article {
						padding: 10px !important;
					}
					table.body .content {
						padding: 0 !important;
					}
					table.body .container {
						padding: 0 !important;
						width: 100% !important;
					}
					table.body .main {
						border-left-width: 0 !important;
						border-radius: 0 !important;
						border-right-width: 0 !important;
					}
					table.body .btn table {
						width: 100% !important;
					}
					table.body .btn a {
						width: 100% !important;
					}
					table.body .img-responsive {
						height: auto !important;
						max-width: 100% !important;
						width: auto !important;
					}
				}

				/* -------------------------------------
						PRESERVE THESE STYLES IN THE HEAD
				------------------------------------- */
				@media all {
					.ExternalClass {
						width: 100%;
					}
					.ExternalClass,
					.ExternalClass p,
					.ExternalClass span,
					.ExternalClass font,
					.ExternalClass td,
					.ExternalClass div {
						line-height: 100%;
					}
					.apple-link a {
						color: inherit !important;
						font-family: inherit !important;
						font-size: inherit !important;
						font-weight: inherit !important;
						line-height: inherit !important;
						text-decoration: none !important;
					}
					#MessageViewBody a {
						color: inherit;
						text-decoration: none;
						font-size: inherit;
						font-family: inherit;
						font-weight: inherit;
						line-height: inherit;
					}
					.btn-primary table td:hover {
						background-color: #34495e !important;
					}
					.btn-primary a:hover {
						background-color: #34495e !important;
						border-color: #34495e !important;
					}
				}

			</style>
		</head>
		<body>
			<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
				<tr>
					<td>&nbsp;</td>
					<td class="container">
						<div class="content">

							<!-- START CENTERED WHITE CONTAINER -->
							<table role="presentation" class="main">

								<!-- START MAIN CONTENT AREA -->
								<tr>
									<td class="wrapper">
										<table role="presentation" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td>
													<p>Hi $name,</p>
													$content
													<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
														<tbody>
															<tr>
																<td align="left">
																	<table role="presentation" border="0" cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td> <a href="$link" target="_blank">Open Admin Dashboard</a> </td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>

							<!-- END MAIN CONTENT AREA -->
							</table>
							<!-- END CENTERED WHITE CONTAINER -->

						</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</body>
		</html>
		EOD;
	}
}
