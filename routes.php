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
    case 'home':
        include '../views/home.view.php';
        break;
    case 'shop':
        include '../views/shop.view.php';
        break;
    case 'branches':
        include '../views/branches.view.php';
        break;
    default:
        include '../views/signup.view.php';
        break;
}
?>
