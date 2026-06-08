<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;

Events::on("pre_system", function () {

    // ------------------------------------------------------------
    // KEEP DEFAULT CODEIGNITER BEHAVIOR
    // ------------------------------------------------------------
    if (ENVIRONMENT !== "testing") {
        if (ini_get("zlib.output_compression")) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_start(function ($buffer) {
            return $buffer;
        });
    }

    if (CI_DEBUG && !is_cli()) {
        Events::on("DBQuery", "CodeIgniter\\Debug\\Toolbar\\Collectors\\Database::collect");
        service('toolbar')->respond();
    }

    // ------------------------------------------------------------
    // 🔥 DISABLED ALL LICENSE / REMOTE API VALIDATION
    // ------------------------------------------------------------
    // Everything below has been removed:
    // - DotEnv loading
    // - purchase code validation
    // - remote URL API call
    // - file writing checks
    // - domain name validation
    // ------------------------------------------------------------

});
