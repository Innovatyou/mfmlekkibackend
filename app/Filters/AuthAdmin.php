<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    if (!session()->get('isLoggedIn')) {
      return redirect()->to(base_url() . '/login');
    }
    $session = session();

    // Only super_admin (roleId = 1) can access legacy admin features
    if ($session->get('roleId') != 1) {
      return redirect()->to(base_url() . '/dashboard')->with('error', 'You do not have permission to access this area');
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do something here
    //var_dump($request); die;
  }
}
