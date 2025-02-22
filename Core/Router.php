<?php

namespace Core;

require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/../Models/User.php';

use Core\Session;
use Models\User;
use mysqli;
use Exception;
use Models\Cart;

class Router
{
    private $routes = [];
    private $currentMiddleware = [];

    public function add($method, $uri, $controller)
    {
        $uri = '/' . ltrim($uri, '/');
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'middleware' => $this->currentMiddleware
        ];
        $this->currentMiddleware = [];
        return $this;
    }

    public function middleware($middleware)
    {
        $this->currentMiddleware[] = $middleware;
        return $this;
    }

    public function dispatch($method, $uri)
    {
        $uri = '/' . ltrim($uri, '/');
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && $route['uri'] == $uri) {
                // Execute middleware
                foreach ($route['middleware'] as $middleware) {
                    if (is_callable($middleware)) {
                        $middleware();
                    } elseif (method_exists($middleware, 'handle')) {
                        $middleware->handle();
                    } else {
                        throw new Exception("Middleware must be a callable or an object with a handle method");
                    }
                }

                [$controller, $action] = explode('@', $route['controller']);

                // Remove namespace if present
                $controllerClass = basename($controller);

                // Handle both namespaced and non-namespaced controllers
                $controllerPath = __DIR__ . '/../Http/Controllers/' . $controllerClass . '.php';

                if (!file_exists($controllerPath)) {
                    throw new Exception("Controller file not found: {$controllerPath}");
                }

                require_once $controllerPath;

                // Initialize dependencies
                $db = new mysqli("localhost", "root", "", "ecomart_db");
                $session = new Session();
                $validator = new \Core\Validator();
                $user = new User($db);
                $cart = new Cart($db, $session);

                // Handle both namespaced and non-namespaced controllers
                $controllerClass = (strpos($controller, '\\') !== false) ? $controller : $controllerClass;

                if (!class_exists($controllerClass)) {
                    throw new Exception("Controller class {$controllerClass} not found");
                }

                $controllerInstance = new $controllerClass($db, $session, $validator, $user, $cart);

                if (!method_exists($controllerInstance, $action)) {
                    throw new Exception("Method {$action} not found in {$controllerClass}");
                }

                return $controllerInstance->$action();
            }
        }

        header('HTTP/1.1 404 Not Found');
        require_once __DIR__ . '/../views/404.php';
    }
}