<?php

namespace Models;
use mysqli;
use Exception;

class User
{
    private $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        try {
            $this->db->begin_transaction();

            // Insert into users table
            $stmt = $this->db->prepare('
                INSERT INTO users (email, mobile_number, password, terms_accepted, created_at, updated_at, role) 
                VALUES (?, ?, ?, ?, NOW(), NOW(), ?)
            ');

            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->db->error);
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bind_param(
                "sssis",
                $data['email'],
                $data['mobile_number'],
                $hashedPassword,
                $data['terms_accepted'],
                $data['role']
            );

            $stmt->execute();
            $userId = $this->db->insert_id;
            $stmt->close();

            // Insert into user_profiles table with all fields
            $stmt = $this->db->prepare('
                INSERT INTO user_profiles (user_id, first_name, last_name, gender, birthdate, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ');

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param(
                "issss",
                $userId,
                $data['first_name'],
                $data['last_name'],
                $data['gender'],
                $data['birthdate']
            );
            $stmt->execute();
            $stmt->close();

            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
