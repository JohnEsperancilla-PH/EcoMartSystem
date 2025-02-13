<?php

class ErrorController
{
    public function showError()
    {
        require_once __DIR__ . '/../../views/404.php'; 
        exit();
    }
}
