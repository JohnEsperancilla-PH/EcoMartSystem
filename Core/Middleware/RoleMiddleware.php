<?php
class RoleMiddleware {
    private $session;
    private $db;

    public function __construct(Session $session, Database $db) {
        $this->session = $session;
        $this->db = $db;
    }

    public function handle($role) {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            header('Location: /login');
            exit();
        }

        $stmt = $this->db->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user['role'] !== $role) {
            header('Location: /unauthorized');
            exit();
        }
    }
}