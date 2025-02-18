<?php

namespace Core\Middleware;

use Core\Session;

class RoleMiddleware
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle($allowedRoles)
    {
        $userRole = $this->session->get('user_role');

        if (!$userRole || !in_array($userRole, (array) $allowedRoles)) {
            header('Location: /unauthorized');
            exit();
        }
    }
}
