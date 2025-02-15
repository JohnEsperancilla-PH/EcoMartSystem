<?php

require_once DIR . '/Core/Database.php';
require_once DIR . '/Core/Session.php';
require_once DIR . '/Models/User.php';

use Core\Session;

$db = new Database();
$session = new Session();

if (!$session->get('user_id')) {
    header('Location: /signup');
    exit();
}

require_once DIR . '/views/auth/setup.view.php';