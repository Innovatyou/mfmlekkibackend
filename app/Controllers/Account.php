<?php

namespace App\Controllers;

use App\Models\Account_model as accountmodel;
use App\Models\Verify_model as verifymodel;

class Account extends BaseController
{
  public function __construct()
  {
  }



  //verify email when user clicks on the link
  function verifyEmailLink($code)
  {
    $verifymodel = new verifymodel();
    // Check activation id in database
    $row = $verifymodel->checkActivationDetails($code);
    if ($row) {
      //delete activation details
      $verifymodel->deleteActivationDetails($code);
      //update user to verified
      $accountmodel = new accountmodel();
      $accountmodel->updateUserVerfication($row->email);
      //redirect to message page with message for user
      $this->viewdata['title'] = 'Congratulations';
      $this->viewdata['message'] = 'Your account have been successfully verified.';
      return view('success', $this->viewdata); // this will load the view file
    } else {
      //redirect to message page with message for user
      $this->viewdata['title'] = 'OOOPS!!!';
      $this->viewdata['message'] = 'Your email address cannot be verified at the moment.';
      return view('failure', $this->viewdata); // this will load the view file
    }
  }

  function resetLink($code)
  {
    $verifymodel = new verifymodel();
    // Check activation id in database
    $row = $verifymodel->checkActivationDetails($code);
    if ($row) {
      //redirect to message page with message for user
      $this->viewdata['email'] = $row->email;
      $this->viewdata['activation_id'] = $code;
      return view('resetPasswordForm', $this->viewdata);
    } else {
      //redirect to message page with message for user
      $this->viewdata['title'] = 'OOOPS!!!';
      $this->viewdata['message'] = 'Password reset failed. Please try again some other time.';
      return view('failure', $this->viewdata); // this will load the view file
    }
  }

  //change user password
  public function changeUserPassword()
  {
    $email = $this->request->getVar('email');
    $code = $this->request->getVar('activation_id');
    $password1 = $this->request->getVar('password1');
    $password2 = $this->request->getVar('password2');

    $session = session();
    if ($password1 != $password2) {
      $session->setFlashdata('error', "Passwords dont match");
      $this->viewdata['email'] = $email;
      $this->viewdata['activation_id'] = $code;
      return view('resetPasswordForm', $this->viewdata);
    }

    $verifymodel = new verifymodel();
    $row = $verifymodel->checkActivationDetails($code);
    if (!$row) {
      //redirect to message page with message for user
      $this->viewdata['title'] = 'OOOPS!!!';
      $this->viewdata['message'] = 'Password reset failed. Please try again some other time.';
      return view('failure', $this->viewdata); // this will load the view file
    }

    //
    $accountmodel = new accountmodel();
    $accountmodel->updateUserPassword($email, $password1);
    //delete activation details
    $verifymodel->deleteActivationDetails($code);
    $this->viewdata['title'] = 'Congratulations';
    $this->viewdata['message'] = 'Your password reset was successful. You can now login with your new password.';
    return view('success', $this->viewdata);
  }
}
