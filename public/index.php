<?php
define('DIR', dirname(__DIR__));

require_once DIR . '/Core/Database.php';
require_once DIR . '/Core/Router.php';

use Core\Router;

$router = new Router();

require_once DIR . '/routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


session_start();


try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
}
