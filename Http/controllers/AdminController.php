<?php

class AdminController
{
public function dashboard()
{
    $session = new \Core\Session();
    if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
        header('Location: /login');
        exit();
    }
    
    include_once DIR . '/public/admin/dashboard.php';
}
}
