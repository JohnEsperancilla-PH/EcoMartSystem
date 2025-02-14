<?php

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', __DIR__);
define('APP_URL', 'http://localhost/EcoMartSystem'); // Adjust this to your actual URL

// Load configuration
require_once __DIR__ . '/config/config.php';

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = BASE_PATH . DIRECTORY_SEPARATOR . $file;
    if (file_exists($file)) {
        require $file;
    }
});
