<?php

class Authenticator
{
    private $db;
    private $session;

    public function __construct(PDO $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
    }

    public function attempt($email, $password, $role)
    {
        $stmt = $this->db->prepare('
            SELECT id, email, password, role 
            FROM Users 
            WHERE email = ? AND role = ?
            LIMIT 1
        ');

        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set('user_id', $user['id']);
            $this->session->set('role', $user['role']);
            $this->session->set('email', $user['email']);
            return true;
        }

        return false;
    }

    public function check()
    {
        return $this->session->get('user_id') !== null;
    }

    public function user()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return null;
        }

        $stmt = $this->db->prepare('
            SELECT u.*, up.first_name, up.last_name 
            FROM Users u 
            LEFT JOIN UserProfiles up ON u.id = up.user_id 
            WHERE u.id = ?
        ');

        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function logout()
    {
        $this->session->destroy();
    }
}
