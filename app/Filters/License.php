<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class License implements FilterInterface
{
    private const VERIFY_EVERY_DAYS = 30;
    private const GRACE_DAYS        = 7;

    public function before(RequestInterface $request, $arguments = null)
    {
        $code   = env('ACTIVATION_CODE', '');
        $status = env('ACTIVATION_STATUS', '');

        if (empty($code) || $status !== 'activated') {
            return $this->denyAccess($request, 'This application is not activated. Please enter your purchase code.');
        }

        $lastVerified = (int) env('ACTIVATION_LAST_VERIFIED', 0);
        $daysSince    = (time() - $lastVerified) / 86400;

        if ($daysSince >= self::VERIFY_EVERY_DAYS) {
            $result = $this->pingServer($code, $request->getServer('HTTP_HOST') ?? 'unknown');

            if ($result === 'revoked') {
                self::writeEnv([
                    'ACTIVATION_CODE'   => '',
                    'ACTIVATION_STATUS' => 'revoked',
                ]);
                return $this->denyAccess($request, 'Your license has been revoked. Please contact support.');
            }

            if ($result === true) {
                self::writeEnv(['ACTIVATION_LAST_VERIFIED' => (string) time()]);
            } elseif ($daysSince >= (self::VERIFY_EVERY_DAYS + self::GRACE_DAYS)) {
                // Server unreachable beyond grace period
                return $this->denyAccess($request, 'License verification failed. Please check your internet connection and try again.');
            }
            // else: server unreachable but within grace period - allow through
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function denyAccess(RequestInterface $request, string $message)
    {
        // Return JSON for API requests, redirect for browser requests
        $accept = $request->getServer('HTTP_ACCEPT') ?? '';
        if (str_contains($accept, 'application/json') || str_starts_with($request->getUri()->getPath(), '/api')) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => $message]);
        }

        session()->setFlashdata('license_error', $message);
        return redirect()->to(base_url('activate'));
    }

    private function pingServer(string $code, string $domain): bool|string
    {
        $url = rtrim(env('LICENSE_SERVER_URL', ''), '/') . '/verify.php';
        if (empty(env('LICENSE_SERVER_URL', ''))) {
            return false;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['code' => $code, 'domain' => $domain]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$raw) {
            return false;
        }

        $data = json_decode($raw, true);
        if (!$data) {
            return false;
        }

        if (!empty($data['revoked'])) {
            return 'revoked';
        }

        return !empty($data['success']);
    }

    public static function writeEnv(array $values): void
    {
        $envPath = ROOTPATH . '.env';
        $content = file_exists($envPath) ? file_get_contents($envPath) : '';

        foreach ($values as $key => $value) {
            $escaped = str_replace('"', '\\"', $value);
            $line    = "{$key} = \"{$escaped}\"";

            if (preg_match('/^' . preg_quote($key, '/') . '\s*=/m', $content)) {
                $content = preg_replace('/^' . preg_quote($key, '/') . '\s*=.*/m', $line, $content);
            } else {
                $content = rtrim($content) . "\n" . $line . "\n";
            }
        }

        file_put_contents($envPath, $content);
    }
}
