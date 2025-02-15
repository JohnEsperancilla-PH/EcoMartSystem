<?php
// filepath: /c:/xampp/htdocs/EcoMartSystem/public/admin/dashboard.php

require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Database.php';

// Check if a session is already active before starting a new one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: /login");
    exit();
}

// Correct the path to the view file
require_once __DIR__ . '/../../views/admin/dashboard.view.php';
?>