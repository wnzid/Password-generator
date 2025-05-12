<?php
class User {
    public $username;
    public $password;

    public function __construct($username, $password) {
        $this->username = trim($username);
        $this->password = trim($password);
    }

    public function validate() {
        if (empty($this->username) || empty($this->password)) {
            return false;
        }
        return true;
    }
}
?>