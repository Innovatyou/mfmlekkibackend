<?php
// Render flash messages for 'success', 'error', and 'message' keys.
// Safely escapes all output and handles array values.
$session = session();
$keys = ['success' => 'alert-success', 'error' => 'alert-danger', 'message' => 'alert-danger'];
foreach ($keys as $key => $class) {
    $flash = $session->getFlashdata($key);
    if ($flash !== NULL) {
        echo "<div class='alert {$class} mt-2'>";
        if (is_array($flash)) {
            echo implode('<br>', array_map('esc', $flash));
        } else {
            echo esc($flash);
        }
        echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    }
}
