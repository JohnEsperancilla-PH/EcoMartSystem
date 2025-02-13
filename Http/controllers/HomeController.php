<?php

namespace Http\Controllers;

class HomeController
{
    public function index()
    {
        require_once __DIR__ . '/../../views/index.view.php';
    }
}
