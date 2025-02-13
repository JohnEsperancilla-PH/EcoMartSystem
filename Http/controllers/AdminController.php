<?php

class AdminController
{
    public function dashboard()
    {
        require_once __DIR__ . '/../../views/admin/dashboard.view.php';
        exit();
    }
}
