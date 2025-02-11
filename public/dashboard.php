<?php

require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Core/Middleware/AdminMiddlware.php';

$middleware = new AdminMiddleware();
$middleware->handle();

require_once __DIR__ . '/../../views/admin/dashboard.view.php';
