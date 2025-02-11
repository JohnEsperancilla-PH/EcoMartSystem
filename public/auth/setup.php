<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/User.php';

$db = new Database();
$session = new Session();

if (!$session->get('user_id')) {
    header('Location: /signup');
    exit();
}

require_once __DIR__ . '/../views/auth/setup.view.php';