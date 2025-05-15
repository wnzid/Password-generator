<?php
session_start();
require_once 'config/db.php';

$message= '';

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $db=new Database();
    $conn=$db->connect();

    $username=trim($_POST['username']);
    $password=trim($_POST['password']);

    $sql="SELECT * FROM users WHERE username=:username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if($stmt->rowCount() ===1){
        $user=$stmt->fetch(PDO::FETCH_ASSOC);

          if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user']=$user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $message="Incorrect password.";
        }
    } else {
        $message="User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>User Login</h2>
    <form method="post" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <input type="submit" value="Login">
    </form>

    <p style="color:red;"><?php echo $message; ?></p>
</body>
</html>