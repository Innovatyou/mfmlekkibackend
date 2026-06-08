<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);

require_once SYSTEMPATH . 'bootstrap.php';

// Try to verify views exist
$views_dir = APPPATH . 'Views';
$required_views = [
    'templates/header.php',
    'templates/footer.php',
    'admin/roles/view.php'
];

foreach ($required_views as $view) {
    $full_path = $views_dir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view);
    echo "Checking: $full_path\n";
    if (file_exists($full_path)) {
        echo "✓ EXISTS\n";
    } else {
        echo "✗ NOT FOUND\n";
    }
}
