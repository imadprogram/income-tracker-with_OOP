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

    public function create($user_id, $amount, $description, $date, $category_id){
        $sql = "INSERT INTO expense(user_id , amount , description , date , category_id) VALUES(:user_id , :amount , :description , :date , :category_id)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('amount', $amount);
        $stmt->bindParam('description', $description);
        $stmt->bindParam('date', $date);
        $stmt->bindParam('category_id', $category_id);
        $stmt->execute();

        return true;
    }

    public function getAll($user_id) {
        $sql = "SELECT expense.* , category.name as category_name
                FROM expense 
                LEFT JOIN category ON expense.category_id = category.id
                WHERE expense.user_id = :user_id
                ORDER BY date DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return [];
        }
    }

    public function sum($user_id){
        $sql = "SELECT sum(amount) as sum FROM expense WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['sum'] ?? 0;
    }

    public function getByCategory($user_id , $category) {
        $sql = "SELECT expense.* , category.name as category_name
                FROM expense 
                LEFT JOIN category ON expense.category_id = category.id
                WHERE expense.user_id = :user_id AND expense.category_id = :category
                ORDER BY date DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('category', $category);
        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id , $user_id , $amount , $description , $date) {
        
        $sql = "UPDATE expense SET amount = :amount, description = :description, date = :date WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $id);    
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);

        return $stmt->execute();
    }

    public function delete($user_id , $expense_id) {
        $sql = "DELETE FROM expense WHERE user_id = :user_id AND id = :expense_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('expense_id', $expense_id);
        $stmt->execute();

        return true;
    }
}
