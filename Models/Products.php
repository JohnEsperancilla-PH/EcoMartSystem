<?php

require_once __DIR__ . '/../Core/Database.php';

class Products
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM products";
        $result = $this->conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductsByCategory($categoryName)
    {
        $query = "SELECT p.* FROM products p
                  JOIN categories c ON p.category_id = c.category_id
                  WHERE c.name = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
