<?php

namespace App\Filters;

use CodeIgniter\Filters\CSRF;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Wraps CI4's built-in CSRF filter to fix a PHP 8.3 deprecation:
 * Security::verify() calls json_decode() on the raw body, which throws
 * a deprecation notice (converted to exception) when the body is null.
 */
class CsrfFilter extends CSRF
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Prevent json_decode(null) deprecation in Security::verify() on PHP 8.3
        if ($request->getBody() === null) {
            $request->setBody('');
        }

        return parent::before($request, $arguments);
    }
}
