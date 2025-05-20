<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$message ='';

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $platform=trim($_POST['platform']);
    $password=trim($_POST['password']);
    $username=$_SESSION['user'];

    $db=new Database();
    $conn=$db->connect();

    $sql="INSERT INTO passwords (username, platform, password_value)
            VALUES (:username, :platform, :password)";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':platform', $platform);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        $message="Password saved successfully.";
    } else {
        $message="Error saving password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Save a Password</title>
</head>
<body>
    <div style="max-width: 500px; margin: 60px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Manually Save a Password</h3>

        <form method="post" action="">
            <label>Platform:</label>
            <input type="text" name="platform" required><br><br>

            <label>Password:</label>
            <input type="text" name="password" required><br><br>

            <input type="submit" value="Save Password">
        </form>

        <?php if (!empty($message)): ?>
            <p style="margin-top: 20px; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>