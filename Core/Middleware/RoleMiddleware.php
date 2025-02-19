<?php

namespace Core\Middleware;

use Core\Session;

class RoleMiddleware
{
    private $session;
    private $allowedRoles;

    public function __construct(Session $session, array $allowedRoles)
    {
        $this->session = $session;
        $this->allowedRoles = $allowedRoles;
    }

    public function handle()
    {
        $userRole = $this->session->get('user_role');

        if (!$userRole || !in_array($userRole, (array) $this->allowedRoles)) {
            header('Location: /error?code=403');
            exit();
        }
    }
}
