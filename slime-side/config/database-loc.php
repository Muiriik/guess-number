<?php
class Database{

    // database credentials
    private $host = "localhost";
    private $db_name = "muiiriik_guess_number";
    private $username = "root";
    private $password = "";
    private $conn;

    // database connection
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8_czech_ci");
        } catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}