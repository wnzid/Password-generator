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
    <div style="max-width: 400px; margin: 60px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Login</h3>

        <form method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required><br><br>

            <label>Password:</label>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>

        <?php if (!empty($message)): ?>
            <p style="margin-top: 20px; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>