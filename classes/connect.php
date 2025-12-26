<?php

class Database
{
    private $host = "localhost";
    private $db_name = "smart_wallet_oop";
    private $username = "root";
    private $password = "";
    private $connection;

    public function connect(){
        $this->connection = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->connection = new PDO($dsn, $this->username, $this->password);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error :" . $e->getMessage();
        }

        return $this->connection;
    }
}
