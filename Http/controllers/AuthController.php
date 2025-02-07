<?php

class AuthController {
    private $user;
    private $session;

    public function __construct(User $user, Session $session) {
        $this->session = $session;
        $this->user = $user;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $user_id = $this->user->authenticate($email, $password);

            if ($user_id) {
                $this->session->set('user_id', $user_id);
                header('Location: /dashboard');
                exit;
            }
            return ['error' => 'Invalid credentials'];
        }
    }
}