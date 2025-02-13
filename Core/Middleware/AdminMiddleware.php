<?php

namespace Core\Middleware;

use Core\Session;

class AdminMiddleware
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function handle()
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}
