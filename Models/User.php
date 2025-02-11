<?php
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

            $stmt = $this->db->prepare('
                INSERT INTO UserProfiles (user_id, created_at, updated_at)
                VALUES (?, NOW(), NOW())
            ');

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateProfile($userId, $data)
    {
        $stmt = $this->db->prepare('
            UPDATE UserProfiles 
            SET first_name = ?, last_name = ?, gender = ?, birthdate = ?, updated_at = NOW()
            WHERE user_id = ?
        ');

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param(
            "ssssi",
            $data['first_name'],
            $data['last_name'],
            $data['gender'],
            $data['birthdate'],
            $userId
        );
        return $stmt->execute();
    }
}
