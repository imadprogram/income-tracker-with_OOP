<?php
class Expense
{
    private $conn;

    private $amount;
    private $description;
    private $date;
    private $category;
    private $user_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($user_id, $amount, $description, $date, $category)
    {
        $sql = "INSERT INTO expense(user_id , amount , description , date , category) VALUES(:user_id , :amount , :description , :date , :category)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('amount', $amount);
        $stmt->bindParam('description', $description);
        $stmt->bindParam('date', $date);
        $stmt->bindParam('category', $category);
        $stmt->execute();

        return true;
    }

    public function getAll() {}

    public function sum($user_id){
        $sql = "SELECT sum(amount) as sum FROM income WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['sum'] ?? 0;
    }

    public function getById() {}

    public function getByCategory() {}

    public function update() {}

    public function delete() {}
}
