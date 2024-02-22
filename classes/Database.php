<?php

class Database
{
    private $host = 'infolink.pagemfbank.com';
    private $db_name = "dbMemoPortal";
    private $username = "phpmyadmin";     
    private $password = "LetMeP@ss12!";
    public $conn;

    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . "; dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection Error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
