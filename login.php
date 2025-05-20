<?php
session_start();
require_once 'config/db.php';

$message='';

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $db=new Database();
    $conn=$db->connect();

    $username=trim($_POST['username']);
    $password=trim($_POST['password']);

    $sql="SELECT * FROM users WHERE username = :username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount()===1) {
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
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">

    <div style="max-width: 400px; margin: 80px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Login</h3>

        <form method="post" action="" style="margin-top: 30px;">
            <label>Username:</label>
            <input type="text" name="username" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <label>Password:</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <input type="submit" value="Login" style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
        </form>

        <?php if (!empty($message)): ?>
            <p style="margin-top: 20px; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account?
            <a href="signup.php" style="text-decoration: none; color: #007bff;">Sign up here</a>
        </p>
    </div>

</body>
</html>