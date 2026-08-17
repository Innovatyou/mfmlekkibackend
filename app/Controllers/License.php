<?php

namespace App\Controllers;

use App\Filters\License as LicenseFilter;

class License extends BaseController
{
    public function activate()
    {
        return view('license/activate', [
            'error'   => session()->getFlashdata('license_error') ?? session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ]);
    }

    public function process()
    {
        $code   = strtoupper(trim($this->request->getPost('purchase_code') ?? ''));
        $domain = $this->request->getServer('HTTP_HOST') ?? 'unknown';

        if (empty($code)) {
            return redirect()->to(base_url('activate'))->with('error', 'Please enter your purchase code.');
        }

        $serverUrl = rtrim(env('LICENSE_SERVER_URL', ''), '/') . '/verify.php';

        if (empty(env('LICENSE_SERVER_URL', ''))) {
            return redirect()->to(base_url('activate'))->with('error', 'License server URL is not configured. Contact the administrator.');
        }

        $ch = curl_init($serverUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'code'     => $code,
                'domain'   => $domain,
                'activate' => '1',
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            // Some hosts (this license server included — LiteSpeed behind
            // Cloudflare) return a 406 HTML page for requests with no
            // User-Agent, which PHP's cURL sends by default. That HTML isn't
            // valid JSON, so every activation silently failed with a
            // misleading "invalid code" message regardless of the code.
            CURLOPT_USERAGENT      => 'ChurchBackend-License-Client/1.0',
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$raw) {
            return redirect()->to(base_url('activate'))->with('error', 'Could not reach the license server. Check your internet connection and try again.');
        }

        $data = json_decode($raw, true);

        if ($data === null) {
            // The server responded, but not with valid JSON — e.g. a host-level
            // block page (this is what a missing User-Agent used to trigger here)
            // or a stray PHP warning printed ahead of the JSON. Either way it's
            // not an actual "your code is wrong" verdict, so surfacing it as
            // "Invalid purchase code" would be misleading. Log the raw body so
            // it can be diagnosed from this app's logs without needing access
            // to the license server itself.
            log_message('error', 'License verify: non-JSON response from license server. Raw body: ' . substr($raw, 0, 2000));
            return redirect()->to(base_url('activate'))->with('error', 'The license server returned an unexpected response. Please try again shortly or contact support.');
        }

        if (empty($data['success'])) {
            $msg = $data['message'] ?? 'Invalid purchase code.';
            return redirect()->to(base_url('activate'))->with('error', $msg);
        }

        LicenseFilter::writeEnv([
            'ACTIVATION_CODE'          => $code,
            'ACTIVATION_STATUS'        => 'activated',
            'ACTIVATION_LAST_VERIFIED' => (string) time(),
        ]);

        return redirect()->to(base_url('activate'))->with('success', 'License activated successfully!');
    }
}
