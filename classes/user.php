<?php
class User {
    private $conn;

    private $id;
    private $name;
    private $email;
    private $password;


    public function __construct($db){
        $this->conn = $db;
    }

    public function register($name , $email , $password){
        
        $hashed_pass = password_hash($password , PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(name , email , password) VALUES(:name , :email , :password)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_pass);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function login($email , $password){

        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam('email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password , $row['password'])){

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];

                return true;
            }else{
                return false;
            }
        }
    }
    public function logout(){
        session_unset();
        session_destroy();

        header('Location: index.php');
    }
}
?>