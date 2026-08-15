<?php

namespace App\Controllers;

use App\Models\Login_model as loginmodel;
use App\Models\Verify_model as verifymodel;
use App\Models\Settings_model as settingsmodel;

class Login extends BaseController
{
    protected $session;

    // Max failed attempts before lockout; lockout window in seconds
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TTL  = 900; // 15 minutes

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
    }

    public function index()
    {
        if ($this->session->get('isLoggedIn') === true) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('login', $this->viewdata);
    }

    public function authenticate()
    {
        $ip       = service('request')->getIPAddress();
        $cacheKey = 'login_attempts_' . md5($ip);
        $cache    = \Config\Services::cache();

        // Check lockout
        $attempts = (int) ($cache->get($cacheKey) ?? 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->session->setFlashdata('message', 'Too many failed attempts. Please try again in 15 minutes.');
            return redirect()->to(base_url() . '/login');
        }

        $valid = $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[1]',
        ]);

        if (!$valid) {
            return view('login', ['validation' => $this->validator] + ($this->viewdata ?? []));
        }

        $email    = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $loginmodel = new loginmodel();
        $auth_user  = $loginmodel->authenticate($email, $password);

        if ($auth_user !== false) {
            // Successful login — clear attempt counter
            $cache->delete($cacheKey);

            $this->session->set([
                'userId'    => $auth_user->email,
                'name'      => $auth_user->fullname,
                'role'      => $auth_user->role,
                'roleId'    => $auth_user->role_id,
                'status'    => 0,
                'logo'      => $auth_user->logo !== ''
                                ? base_url() . '/uploads/churches/' . $auth_user->logo
                                : '',
                'isLoggedIn' => true,
            ]);

            return redirect()->to(base_url('dashboard'));
        }

        // Failed — increment attempt counter
        $cache->save($cacheKey, $attempts + 1, self::LOCKOUT_TTL);

        $remaining = self::MAX_ATTEMPTS - ($attempts + 1);
        $msg = $remaining > 0
            ? "Email or password is incorrect. {$remaining} attempt(s) remaining."
            : 'Too many failed attempts. Account locked for 15 minutes.';

        $this->session->setFlashdata('message', $msg);
        return redirect()->to(base_url() . '/login');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url() . '/login');
    }

    public function forgotPassword()
    {
        if ($this->session->get('isLoggedIn') === true) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('forgot_password', $this->viewdata);
    }

    public function sendAdminResetEmail()
    {
        $email = trim($this->request->getVar('email') ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlashdata('error', 'Please enter a valid email address.');
            return redirect()->to(base_url('forgot-password'));
        }

        $loginmodel = new loginmodel();
        if ($loginmodel->emailExists($email)) {
            try {
                $settingsmodel = new settingsmodel();
                $adminsettings = $settingsmodel->getSettings();
                $emailconfig   = $settingsmodel->getEmailConfig();
                $link          = $this->getAdminResetLink($email);
                $htmlContent   = '<p>Please click the link below to reset your admin password.</p>';
                $this->sendEmail(
                    $adminsettings->churchname ?? 'MyChurchApp',
                    $emailconfig,
                    $email,
                    'Password Reset',
                    $this->getActivationEmailTemplate($link, 'Reset Password', $htmlContent)
                );
            } catch (\Throwable $e) {
                log_message('error', 'Admin password reset email failed: ' . $e->getMessage());
            }
        }

        // Always show the same message to prevent email enumeration
        $this->session->setFlashdata('success', 'If that email exists in our system, you will receive a reset link shortly.');
        return redirect()->to(base_url('forgot-password'));
    }

    public function adminResetForm(string $code)
    {
        $verifymodel = new verifymodel();
        $row = $verifymodel->checkActivationDetails($code);
        if (!$row) {
            $this->viewdata['title']   = 'Link Expired';
            $this->viewdata['message'] = 'This password reset link is invalid or has already been used.';
            return view('failure', $this->viewdata);
        }
        $this->viewdata['email']         = $row->email;
        $this->viewdata['activation_id'] = $code;
        return view('admin_reset_password', $this->viewdata);
    }

    public function adminChangePassword()
    {
        $email     = $this->request->getVar('email');
        $code      = $this->request->getVar('activation_id');
        $password1 = $this->request->getVar('password1');
        $password2 = $this->request->getVar('password2');

        if ($password1 !== $password2) {
            $this->session->setFlashdata('error', 'Passwords do not match.');
            $this->viewdata['email']         = $email;
            $this->viewdata['activation_id'] = $code;
            return view('admin_reset_password', $this->viewdata);
        }

        if (strlen($password1) < 6) {
            $this->session->setFlashdata('error', 'Password must be at least 6 characters.');
            $this->viewdata['email']         = $email;
            $this->viewdata['activation_id'] = $code;
            return view('admin_reset_password', $this->viewdata);
        }

        $verifymodel = new verifymodel();
        if (!$verifymodel->checkActivationDetails($code)) {
            $this->viewdata['title']   = 'Link Expired';
            $this->viewdata['message'] = 'This password reset link is invalid or has already been used.';
            return view('failure', $this->viewdata);
        }

        $loginmodel = new loginmodel();
        $loginmodel->updatePassword($email, $password1);
        $verifymodel->deleteActivationDetails($code);

        $this->session->setFlashdata('success', 'Password updated. You can now sign in with your new password.');
        return redirect()->to(base_url('login'));
    }

    private function getAdminResetLink(string $email): string
    {
        $verifymodel = new verifymodel();
        $data = [
            'email'         => $email,
            'activation_id' => $this->generate_string(),
            'agent'         => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'client_ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
        ];
        $verifymodel->insertData($data);
        return base_url('admin-reset/' . $data['activation_id']);
    }
}
