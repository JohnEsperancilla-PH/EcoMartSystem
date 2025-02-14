<?php

$router->add('GET', '/', 'HomeController@index');

$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@register');
$router->add('POST', '/logout', 'AuthController@logout');
$router->add('GET', '/setup-profile', 'AuthController@setupProfile');
$router->add('POST', '/setup-profile', 'AuthController@setupProfile');

// Admin route
$router->add('GET', '/admin/dashboard', 'AdminController@dashboard');

// Customer
$router->add('GET', '/dashboard', 'CustomerController@dashboard');
$router->add('GET', '/shop', 'CustomerController@shop');
$router->add('GET', '/process-order', 'CustomerController@processOrder');
$router->add('GET', '/branches', 'CustomerController@branches');

// Error
$router->add('GET', '/error', 'ErrorController@showError');
