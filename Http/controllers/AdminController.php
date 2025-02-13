<?php

require_once __DIR__ . '/../../Core/Session.php';

use Core\Session;

class AdminController
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function dashboard()
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        require_once __DIR__ . '/../../views/admin/dashboard.view.php';  // Make sure this path is correct
    }
}
