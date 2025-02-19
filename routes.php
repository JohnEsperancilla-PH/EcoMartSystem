<?php

use Core\Middleware\Authenticated;
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
$router->add('GET', '/logout', 'AuthController@logout');

// Admin routes
$router->add('GET', '/dashboard', 'AdminController@dashboard')->middleware(new Authenticated(new Session()));

// Client routes
$router->add('GET', '/shop', 'CustomerController@shop')->middleware(new Authenticated(new Session()));
$router->add('GET', '/branches', 'CustomerController@branches')->middleware(new Authenticated(new Session()));
$router->add('GET', '/process-order', 'CustomerController@processOrder')->middleware(new Authenticated(new Session()));
$router->add('GET', '/contact', 'CustomerController@contact')->middleware(new Authenticated(new Session()));

// Order routes
$router->add('POST', '/api/orders', 'OrderController@createOrder');
$router->add('GET', '/order-confirmation', 'OrderController@confirmOrder');

// Error route
$router->add('GET', '/error', 'ErrorController@showError');

// Return the router instance
return $router;

// <?php

// use Core\Middleware\Authenticated;
// use Core\Middleware\RoleMiddleware;
// use Core\Session;
// use Core\Router;

// if (!isset($router)) {
//     $router = new Router();
// }

// $session = new Session();

// // Public routes
// $router->add('GET', '/', 'HomeController@index');
// $router->add('GET', '/login', 'AuthController@login');
// $router->add('POST', '/login', 'AuthController@login');
// $router->add('GET', '/register', 'AuthController@register');
// $router->add('POST', '/register', 'AuthController@register');
// $router->add('GET', '/logout', 'AuthController@logout');

// // Admin routes
// $router->add('GET', '/dashboard', 'AdminController@dashboard')
//     ->middleware(new Authenticated($session))
//     ->middleware(new RoleMiddleware($session, ['admin']));

// $router->add('GET', '/add-products', 'AdminController@addProduct')
//     ->middleware(new Authenticated($session))
//     ->middleware(new RoleMiddleware($session, ['admin']));

// $router->add('GET', '/orders-history', 'AdminController@orders')
//     ->middleware(new Authenticated($session))
//     ->middleware(new RoleMiddleware($session, ['admin']));

// // Client routes - restrict to customer role
// $router->add('GET', '/shop', 'CustomerController@shop')->middleware(new Authenticated(new Session()));
// $router->add('GET', '/branches', 'CustomerController@branches')->middleware(new Authenticated(new Session()));
// $router->add('GET', '/process-order', 'CustomerController@processOrder')->middleware(new Authenticated(new Session()));
// $router->add('GET', '/contact', 'CustomerController@contact')->middleware(new Authenticated(new Session()));

// $router->add('POST', '/api/orders', 'OrderController@createOrder');
// $router->add('GET', '/order-confirmation', 'OrderController@confirmOrder');

// // Error route
// $router->add('GET', '/error', 'ErrorController@showError');

// // Return the router instance
// return $router;

