<?php

class HomeController
{
    private $session;

    public function __construct($db, $session, $validator, $user)
    {
        $this->session = $session;
    }

    public function index()
    {
        require_once dirname(__DIR__, 2) . '/views/index.view.php';
    }
}
