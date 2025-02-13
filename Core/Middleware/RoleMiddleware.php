<?php

use Core\Session;
class RoleMiddleware {
    private $session;
    private $db;

    public function __construct(Session $session, mysqli $db) {
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
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }


        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || $user['role'] !== $role) {
            header('Location: /unauthorized');
            exit();
        }
    }
}