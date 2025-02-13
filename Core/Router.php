<?php

namespace Core;

require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/../Models/User.php';

use Core\Session;
use Models\User;
use mysqli;
use Exception;

class Router
{
    private $routes = [];

    public function add($method, $uri, $controller)
    {
        $uri = '/' . ltrim($uri, '/');

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function dispatch($method, $uri)
    {
        $uri = '/' . ltrim($uri, '/');
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && $route['uri'] == $uri) {
                [$controller, $action] = explode('@', $route['controller']);

                $controllerPath = __DIR__ . '/../Http/Controllers/' . $controller . '.php';

                if (!file_exists($controllerPath)) {
                    throw new Exception("Controller file not found: {$controllerPath}");
                }

                require_once $controllerPath;

                if (!class_exists($controller)) {
                    throw new Exception("Controller class {$controller} not found");
                }

                // Initialize dependencies
                $db = new mysqli("localhost", "root", "", "ecomart_db");
                $session = new Session();
                $validator = new \Core\Validator(); 
                $user = new User($db);

                // Pass dependencies to the controller
                $controllerClass = new $controller($db, $session, $validator, $user);

                if (!method_exists($controllerClass, $action)) {
                    throw new Exception("Method {$action} not found in {$controller}");
                }

                return $controllerClass->$action();
            }
        }

        header('HTTP/1.1 404 Not Found');
        require_once __DIR__ . '/../views/404.php';
    }
}
