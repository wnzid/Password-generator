<?php
require_once 'classes/Crypto.php';
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

    $key_plain=bin2hex(random_bytes(16));

    $key_encrypted=Crypto::encryptAES($key_plain, $this->password);

    $sql="INSERT INTO users (username, password_hash, user_key) 
            VALUES (:username, :password, :user_key)";
    $stmt=$this->conn->prepare($sql);
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $hashed);
    $stmt->bindParam(":user_key", $key_encrypted);

    return $stmt->execute();
}
}
?>