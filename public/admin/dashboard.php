<?php

require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Middleware/AdminMiddleware.php';

use Core\Middleware\AdminMiddleware;

$middleware = new AdminMiddleware();
$middleware->handle();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../views/admin/dashboard.view.php';
