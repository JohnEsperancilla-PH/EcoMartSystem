<?php

use Core\Middleware\Authenticated;
use Core\Middleware\AdminMiddleware;
use Core\Session;
use Core\Router;

if (!isset($router)) {
    $router = new Router();
}

// Public routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@register');
<<<<<<< HEAD
$router->add('GET', '/setup-profile', 'AuthController@setupProfile');
$router->add('POST', '/setup-profile', 'AuthController@setupProfile');
=======
$router->add('POST', '/logout', 'AuthController@logout');
>>>>>>> f34bee717ea8d066068c0cbc5637ad1bb53915fe

// Admin routes
$router->add('GET', '/dashboard', 'AdminController@dashboard')->middleware(new Authenticated(new Session()));

// Client routes
$router->add('GET', '/shop', 'CustomerController@shop')
    ->middleware(new Authenticated(new Session()));
$router->add('GET', '/branches', 'CustomerController@branches')
    ->middleware(new Authenticated(new Session()));
$router->add('GET', '/process-order', 'CustomerController@processOrder')
    ->middleware(new Authenticated(new Session()));

// Error route
$router->add('GET', '/error', 'ErrorController@showError');

// Return the router instance
return $router;
