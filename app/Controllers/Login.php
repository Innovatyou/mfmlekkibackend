<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\Login_model as loginmodel;
use App\Models\Home_model as homemodel;

class Login extends BaseController
{
  protected $session;

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
  }

  public function index()
  {
    $isLoggedIn = $this->session->get('isLoggedIn');
    if ($isLoggedIn == "" || $isLoggedIn == NULL || $isLoggedIn != TRUE) {
      return view("login", $this->viewdata);
    } else {
      return redirect()->to(base_url() . '/');
    }
  }

  public function authenticate()
  {
    $loginmodel = new loginmodel();
    $input = $this->validate([
      'password' => 'required',
      'email' => 'required|min_length[2]',
    ]);

    if (!$input) {
      return view('login', [
        'validation' => $this->validator
      ]);
    } else {

      $email = $this->request->getVar('email');
      $password = $this->request->getVar('password');
      $auth_user = $loginmodel->authenticate($email, $password);
      //var_dump($auth_user); die;
      if ($auth_user != NULL) {
        $sessionArray = array(
          'userId'        => $auth_user->email,
          'name'        => $auth_user->fullname,
          'role'        => $auth_user->role,
          'roleId'       => $auth_user->role_id,
          'status'        => 0,
          'apitoken'        => getenv('PURCHASE_CODE'),
          'logo' => $auth_user->logo == "" ? "" : base_url() . "/uploads/churches/" . $auth_user->logo,
          'isLoggedIn'    => TRUE
        );

        $this->session->set($sessionArray);
        return redirect()->to(base_url() . '/');
      } else {
        $this->session->setFlashdata('message', 'Email or password mismatch');
        return redirect()->to(base_url() . '/login');
      }
    }
  }



  public function logout()
  {
    unset(
      $_SESSION['userId'],
      $_SESSION['isLoggedIn']
    );
    return redirect()->to(base_url() . '/');
  }
}
