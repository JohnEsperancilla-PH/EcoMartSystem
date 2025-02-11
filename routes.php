<!-- Authentication -->

<?php

$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@register');
$router->add('POST', '/logout', 'AuthController@logout');
$router->add('GET', '/setup-profile', 'AuthController@setupProfile');
$router->add('POST', '/setup-profile', 'AuthController@setupProfile');

// Admin
$router->add('GET', '/admin/dashboard', 'AdminController@dashboard');

// Customer
$router->add('GET', '/dashboard', 'CustomerController@dashboard');