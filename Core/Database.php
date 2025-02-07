<?php
class Database
{
    private $host = ""; 
    private $username = "";
    private $password = "";
    private $database = "";
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function fetch_assoc($result)
    {
        return $result->fetch_assoc();
    }

    public function close()
    {
        $this->conn->close();
    }
}
