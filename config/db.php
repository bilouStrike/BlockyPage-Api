<?php

class db {
 
    private $host = "localhost";
    private $db_name = "blockypage_lib";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    public $conn;
 
    public function getConnection() {
 
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset($this->charset);
        } catch (\mysqli_sql_exception $e) {
            throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
        }
        return $this->conn;

    }
}
   