<?php

namespace Models;

use mysqli;
use Exception;

class UserProfiles
{
    private $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function create($userId, $profileData) {
        try {
            $stmt = $this->db->prepare('
                INSER INTO user_profiles (
                    user_id,
                    first_name,
                    last_name,
                    gender,
                    birth_date,
                    created_at,
                    updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ');

            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->db->error);
            }

            $stmt->bind_param(
                'issss',
                $userId,
                $profileData['first_name'],
                $profileData['last_name'],
                $profileData['gender'],
                $profileData['birth_date']
            );

            $result = $stmt->execute();
            $stmt->close();

            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare('
            SELECT * FROM user_profiles WHERE user_id = ?
        ');

        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $this->db->error);
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        $stmt->close();

        return $profile;
    }
}
