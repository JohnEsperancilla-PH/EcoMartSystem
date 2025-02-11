<?php
class User
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('
                INSERT INTO users (email, mobile_number, password, terms_accepted, created_at, updated_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ');

            $stmt->execute([
                $data['email'],
                $data['mobile_number'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['terms_accepted']
            ]);

            $userId = $this->db->lastInsertId();

            $stmt = $this->db->prepare('
                INSERT INTO UserProfiles (user_id, created_at, updated_at)
                VALUES (?, NOW(), NOW())
            ');

            $stmt->execute([$userId]);

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
            SET first_name = ?,
                last_name = ?,
                gender = ?,
                birthdate = ?,
                updated_at = NOW()
            WHERE user_id = ?
        ');

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['gender'],
            $data['birthdate'],
            $userId
        ]);
    }
}
