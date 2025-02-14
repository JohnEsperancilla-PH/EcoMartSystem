<?php

namespace Core\Middleware;

use Core\Session;

class Authenticated
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle()
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            header('Location: /login');
            exit();
        }

        // Check for admin routes
        $currentRoute = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (strpos($currentRoute, '/admin') === 0 && $this->session->get('user_role') !== 'admin') {
            header('Location: /error?code=403');
            exit();
        }
    }
}