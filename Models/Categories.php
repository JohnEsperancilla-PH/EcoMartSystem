<?php

require_once __DIR__ . '/../Core/Database.php';

class Categories
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllCategories()
    {
        $sql = 'SELECT * FROM categories';
        $result = $this->conn->query($sql);
        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }
}
