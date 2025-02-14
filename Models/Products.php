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
}
