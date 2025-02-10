<?php
class User
{
    private $db;

    public function _construct($db)
    {
        $this->db = $db;
    }

    public function authenticate($email, $password)
    {
        $stmt = $this->db->prepare('SELECT id, password FROM users WHERE email = ?');
        $stmt->execute([$email]); // Fix: Pass email variable instead of string 'email'
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        }
        return false;
    }
}
