<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Http/controllers/AuthController.php';
require_once __DIR__ . '/../../Core/Validator.php';
require_once __DIR__ . '/../../Models/User.php';

use Core\Session;
use Core\Validator;
use Models\User;

$db = new Database();
$session = new Session();
$validator = new Validator();
$user = new User($db->getConnection());

$authController = new AuthController($db->getConnection(), $session, $validator, $user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $authController->login();
    if (isset($result['error'])) {
        $error = $result['error'];
    }
}

require_once __DIR__ . '/../../views/auth/login.view.php';
