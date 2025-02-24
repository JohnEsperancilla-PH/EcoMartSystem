<?php

use Core\Middleware\Authenticated;
use Core\Middleware\RoleMiddleware;
use Core\Session;
use Core\Router;

if (!isset($router)) {
    $router = new Router();
}

$session = new Session(); 

$authMiddleware = new Core\Middleware\Authenticated($session);

// Public routes
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/logout', 'AuthController@logout')->middleware(function() {
    echo "<script>localStorage.removeItem('orderList');</script>";
});

// Admin routes
$router->add('GET', '/dashboard', 'AdminController@dashboard')->middleware(new Authenticated(new Session()));
$router->add('GET', '/add-products', 'AdminController@addProducts')->middleware(new Authenticated(new Session()));
$router->add('POST', '/add-products/create', 'AdminController@createProduct')->middleware(new Authenticated(new Session()));
$router->add('POST', '/add-products/update', 'AdminController@updateProduct')->middleware(new Authenticated(new Session()));
$router->add('POST', '/add-products/delete', 'AdminController@deleteProduct')->middleware(new Authenticated(new Session())); 
$router->add('GET', '/order-history', 'AdminController@orders')->middleware(new Authenticated(new Session()));

$router->add('POST', '/create', 'AdminController@createProduct')->middleware(new Authenticated(new Session()));
$router->add('POST', '/update', 'AdminController@updateProduct')->middleware(new Authenticated(new Session()));
$router->add('POST', '/delete', 'AdminController@deleteProduct')->middleware(new Authenticated(new Session()));

$router->add('POST', '/update-order', 'AdminController@updateOrder')->middleware(new Authenticated(new Session()));
$router->add('POST', '/delete-order', 'AdminController@deleteOrder')->middleware(new Authenticated(new Session()));


// Client routes
$router->add('GET', '/shop', 'CustomerController@shop')
    ->middleware(new Authenticated($session))
    ->middleware(new RoleMiddleware($session, ['customer']));

$router->add('GET', '/branches', 'CustomerController@branches')
    ->middleware(new Authenticated($session))
    ->middleware(new RoleMiddleware($session, ['customer']));

$router->add('GET', '/process-order', 'CustomerController@processOrder')
    ->middleware(new Authenticated($session))
    ->middleware(new RoleMiddleware($session, ['customer']));

$router->add('GET', '/contact', 'CustomerController@contact')
    ->middleware(new Authenticated($session))
    ->middleware(new RoleMiddleware($session, ['customer']));

$router->add('POST', '/orders', '\Http\controllers\OrderController@createOrder')
    ->middleware(new Authenticated($session))
    ->middleware(new RoleMiddleware($session, ['customer']));


// Error route
$router->add('GET', '/error', 'ErrorController@showError');

// Return the router instance
return $router;
