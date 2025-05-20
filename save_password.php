<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$message='';

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $platform=trim($_POST['platform']);
    $appUsername=trim($_POST['app_username']);
    $password=trim($_POST['password']);
    $username=$_SESSION['user'];

    $db=new Database();
    $conn=$db->connect();

    $sql="INSERT INTO passwords (username, platform, platform_username, password_value)
            VALUES (:username, :platform, :app_username, :password)";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':platform', $platform);
    $stmt->bindParam(':app_username', $appUsername);
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
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">

    <div style="max-width: 500px; margin: 80px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Manually Save a Password</h3>

        <form method="post" action="" style="margin-top: 30px;">
            <label>Platform:</label>
            <input type="text" name="platform" required placeholder="e.g. Gmail, Facebook" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <label>Username for Platform:</label>
            <input type="text" name="app_username" required placeholder="e.g. john@gmail.com" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <label>Password:</label>
            <input type="text" name="password" required placeholder="Enter the password" style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <input type="submit" value="Save Password" style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
        </form>

        <?php if (!empty($message)): ?>
            <p style="margin-top: 20px; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" style="text-decoration: none; padding: 8px 20px; background-color: #6c757d; color: white; border-radius: 6px;">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>