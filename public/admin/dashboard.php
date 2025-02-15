<?php
// filepath: /c:/xampp/htdocs/EcoMartSystem/public/admin/dashboard.php

require_once DIR . '/Core/Session.php';
require_once DIR . '/Core/Database.php';

<<<<<<< HEAD
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
=======
require_once DIR . '/views/admin/dashboard.view.php';
>>>>>>> 8c3d9705956286d129fd38574834c1c2fb7e3a96
