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
    <h2>Manually Save a Password</h2>
    <form method="post">
        <label>Platform:</label><br>
        <input type="text" name="platform" required><br><br>

        <label>Password:</label><br>
        <input type="text" name="password" required><br><br>

        <input type="submit" value="Save Password">
    </form>

    <p><?php echo $message; ?></p>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>