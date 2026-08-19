<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Adds CORS headers to every response so browser-based clients (the Flutter
 * web build, the Next.js landing frontend) can read API responses. Native
 * Android/iOS clients don't send/require these headers, so this was never
 * needed until a browser client was added.
 *
 * No Access-Control-Allow-Credentials header is set, so reflecting the
 * request Origin here does not expose cookie-authenticated (admin/session)
 * responses to other origins.
 */
class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nothing to do before routing/controller dispatch.
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $origin = $request->getHeaderLine('Origin') ?: '*';
        $response->setHeader('Access-Control-Allow-Origin', $origin);
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Mobile-Token');
        $response->setHeader('Access-Control-Max-Age', '86400');
        $response->setHeader('Vary', 'Origin');
    }
}
