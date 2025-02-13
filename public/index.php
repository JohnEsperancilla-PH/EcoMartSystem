<?php
// C:\Users\poyhi\OneDrive\EcoMart\EcoMartSystem\public\index.php

define('DIR', dirname(__DIR__));

// Include necessary core files
require_once DIR . '/Core/Database.php';
require_once DIR . '/Core/Router.php';
require_once DIR . '/Core/Response.php';
require_once DIR . '/Core/Functions.php';
require_once DIR . '/Core/Session.php';

use Core\Router;

// Initialize router
$router = new Router();

// Load routes
require_once DIR . '/routes.php';

// Get current URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Start session
session_start();

// Dispatch the route
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo $e->getMessage(); // For debugging - remove in production
}

if ($uri === '/' || basename($uri) === 'index.php') {
    require_once DIR . '/views/index.view.php';
}