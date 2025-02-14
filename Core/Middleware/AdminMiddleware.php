<?php

use Core\Session;

class AdminMiddleware
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle()
    {
        if (!$this->session->get('authenticated')) {
            header('Location: /login');
            exit();
        }
        if ($this->session->get('role') !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}
