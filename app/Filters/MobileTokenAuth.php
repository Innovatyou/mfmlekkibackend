<?php

namespace App\Filters;

use App\Models\Account_model;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

// Verifies the mobile Bearer token for the Marketplace/Partnership/Counseling/MemberCare
// mobile endpoints, and rejects requests whose claimed "email" identity doesn't match
// the token's owner. The rest of the mobile API is untouched.
class MobileTokenAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $this->extractToken($request);
        if (!$token) {
            return $this->deny('Missing authentication token.');
        }

        $model = new Account_model();
        $user = $model->getUserByApiToken($token);
        if (!$user) {
            return $this->deny('Invalid or expired authentication token.');
        }

        $claimedEmail = $this->extractEmail($request);
        if ($claimedEmail !== null && strcasecmp($claimedEmail, $user->email) !== 0) {
            return $this->deny('Token does not match the provided identity.', 403);
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function extractToken(RequestInterface $request): ?string
    {
        $header = $request->getServer('HTTP_AUTHORIZATION');
        if (!$header && function_exists('getallheaders')) {
            $header = getallheaders()['Authorization'] ?? null;
        }
        if ($header && preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractEmail(RequestInterface $request): ?string
    {
        if (isset($_POST['email'])) {
            return trim((string) $_POST['email']);
        }
        if (isset($_POST['data'])) {
            $decoded = json_decode($_POST['data']);
            if (isset($decoded->email)) {
                return trim((string) $decoded->email);
            }
        }
        $rawBody = $request->getBody();
        if (!empty($rawBody)) {
            $decoded = json_decode($rawBody, true);
            if (is_array($decoded)) {
                if (isset($decoded['data']['email'])) {
                    return trim((string) $decoded['data']['email']);
                }
                if (isset($decoded['email'])) {
                    return trim((string) $decoded['email']);
                }
            }
        }
        return null;
    }

    private function deny(string $message, int $code = 401)
    {
        return service('response')
            ->setStatusCode($code)
            ->setJSON(['status' => 'error', 'message' => $message]);
    }
}
