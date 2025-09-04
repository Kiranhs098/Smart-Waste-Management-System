<?php
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'donation_center';
    public $conn;

    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function escape_string($string) {
        return $this->conn->real_escape_string($string);
    }

    public function __destruct() {
        // Close connection
        $this->conn->close();
    }
}