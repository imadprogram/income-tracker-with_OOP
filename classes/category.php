<?php
class Category {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create($name , $type){
        $sql = "INSERT INTO category(name , type) VALUES(:name , :type)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('name', $name);
        $stmt->bindParam('type', $type);
        $stmt->execute();

        return true;
    }

    public function getAllCategories($type){
        if($type == 'all'){
            $sql = "SELECT * FROM category";
            $stmt = $this->conn->prepare($sql);
            // $stmt->bindParam('type', $type);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }else{
            $sql = "SELECT * FROM category WHERE type = :type";

            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindParam('type', $type);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>