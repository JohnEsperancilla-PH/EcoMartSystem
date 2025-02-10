<!-- Authentication -->

<?php

$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');

// $page = isset($_GET['page']) ? $_GET['page'] : 'signup';