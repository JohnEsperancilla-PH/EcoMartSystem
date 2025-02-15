<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Validator.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Http/controllers/AuthController.php';

use Core\Session;
use Core\Validator;
use Models\User;

// Initialize dependencies
$db = new Database();
$session = new Session();
$validator = new Validator();
$user = new User($db->getConnection());

// Initialize AuthController
$authController = new AuthController($db->getConnection(), $session, $validator, $user);

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->register();
    exit();
}

// Display signup view
require_once __DIR__ . '/../../views/auth/signup.view.php';
