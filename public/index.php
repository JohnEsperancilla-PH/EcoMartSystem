<?php
define('DIR', dirname(__DIR__));

// Autoloader
spl_autoload_register(function ($class) {
    $file = DIR . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once DIR . '/Core/Database.php';
require_once DIR . '/Core/Router.php';

// Get the router instance from routes.php
$router = require_once DIR . '/routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Start session 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}