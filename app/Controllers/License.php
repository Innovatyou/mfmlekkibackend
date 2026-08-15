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
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$raw) {
            return redirect()->to(base_url('activate'))->with('error', 'Could not reach the license server. Check your internet connection and try again.');
        }

        $data = json_decode($raw, true);

        if (!$data || empty($data['success'])) {
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
