<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use App\Filters\CsrfFilter;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use App\Filters\Auth;
use App\Filters\NoAuth;
use App\Filters\AuthAdmin;
use App\Filters\AuthClient;
use App\Filters\AuthorizePermission;
use App\Filters\AuthorizeRole;
use App\Filters\License;
use App\Filters\Cors;
use App\Filters\MobileTokenAuth;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'        => CsrfFilter::class,
        'toolbar'     => DebugToolbar::class,
        'honeypot'    => Honeypot::class,
        'auth'        => Auth::class,
        'authadmin'   => AuthAdmin::class,
        'authclient'  => AuthClient::class,
        'noauth'      => NoAuth::class,
        'permission'  => AuthorizePermission::class,
        'role'        => AuthorizeRole::class,
        'license'     => License::class,
        'cors'        => Cors::class,
        'mobiletoken' => MobileTokenAuth::class,
    ];

    public $globals = [
        'before' => [
            // CSRF disabled globally - this is a mobile API backend; no browser forms
            'license' => ['except' => ['activate', 'activate/process', 'login', 'authenticate', 'logout']],
        ],
        'after' => [
            'cors',
        ],
    ];

    public function __construct()
    {
        if (ENVIRONMENT === 'development') {
            $this->globals['after'][] = 'toolbar';
        }
        parent::__construct();
    }

    public $methods = [];

    public $filters = [];
}
