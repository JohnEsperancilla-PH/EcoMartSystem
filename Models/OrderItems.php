<?php

namespace Models;

class OrderItems
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

}