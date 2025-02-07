<!-- Authentication -->

<?php

$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');