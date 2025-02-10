<?php
class Authenticated
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle()
    {
        if (!$this->session->get('user_id')) {
            header('Location: /login');
            exit();
        }
    }
}
