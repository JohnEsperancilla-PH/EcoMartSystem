<?php

require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Middleware/AdminMiddleware.php';

use Core\Middleware\AdminMiddleware;

$middleware = new AdminMiddleware();
$middleware->handle();

require_once __DIR__ . '/../views/admin/dashboard.view.php';
