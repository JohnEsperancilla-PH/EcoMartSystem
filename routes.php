<!-- Authentication -->

<?php

$router->add('GET', '/login', 'AuthController@login');
$router->add('POST', '/login', 'AuthController@login');

$page = isset($_GET['page']) ? $_GET['page'] : 'signup';

switch ($page) {
    case 'login':
        include '../views/login.view.php';
        break;
    case 'setup':
        include '../views/setup.view.php';
        break;
    default:
        include '../views/signup.view.php';
        break;
}
?>
