<?php
// router.php
if (php_sapi_name() === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $path = __DIR__ . $url['path'];
    if (is_file($path)) {
        return false; // serve the requested resource as-is
    }
}
require __DIR__ . '/index.php';