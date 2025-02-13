<?php

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
