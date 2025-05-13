<?php
class User{
    public $username;
    public $password;
    private $conn;

    public function __construct($username, $password, $conn) {
        $this->username=trim($username);
        $this->password=trim($password);
        $this->conn=$conn;
    }

    public function validate() {
        return !empty($this->username) && !empty($this->password);
    }

    public function save() {
        $hashed=password_hash($this->password, PASSWORD_DEFAULT);

        $sql="INSERT INTO users (username, password_hash) VALUES (:username, :password)";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed);

        return $stmt->execute();
    }
}
?>