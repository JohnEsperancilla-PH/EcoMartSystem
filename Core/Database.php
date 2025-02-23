<?php

namespace Core;

use Exception;
use mysqli;

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ecomart_db";
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

            if ($this->conn->connect_error) {
                error_log("Database connection failed: " . $this->conn->connect_error);
                throw new Exception("Database connection failed: " . $this->conn->connect_error);
            }

            return $this->conn;
        } catch (Exception $e) {
            error_log("Exception in getConnection: " . $e->getMessage());
            throw $e;
        }
    }

    public function close()
    {
        $this->conn->close();
    }
}
