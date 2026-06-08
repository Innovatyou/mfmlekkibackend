<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\Permission;

class AuthorizePermission implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not alter the request or response
     * but may alter the response.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $isLoggedIn = $session->get('isLoggedIn');

        // Check if user is logged in
        if (!$isLoggedIn) {
            return redirect()->to(base_url('login'))->with('message', 'Please login first');
        }

        // If no specific permissions required, allow access
        if (empty($arguments)) {
            return;
        }

        $roleId = $session->get('roleId');
        $permissionModel = new Permission();

        // Check if user has required permission
        $hasPermission = false;
        foreach ($arguments as $permission) {
            if ($permissionModel->hasPermission($roleId, $permission)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action');
        }

        return null;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop processing the request and assume that the next
     * controller has already been called.
     *
     * Possible extension points:
     * - Modify the final output
     * - Add headers to response
     * - Cache the response
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
