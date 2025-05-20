<?php
session_start();

$pageTitle="Dashboard";

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f8f8f8; padding: 0; margin: 0;">

    <div style="background-color: #ffffff; padding: 20px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-weight: 600;">Password Manager</h2>
        <a href="logout.php" style="text-decoration: none; background-color: #dc3545; color: white; padding: 8px 16px; border-radius: 6px;">Logout</a>
    </div>

    <div style="max-width: 600px; margin: 60px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); text-align: center;">
        <h3>Welcome, <?php echo htmlspecialchars($username); ?>!</h3>
        <p>This is your dashboard. What would you like to do?</p>

        <div style="margin-top: 30px;">
            <a href="generate.php" style="display: inline-block; margin: 10px; padding: 10px 25px; background-color: #007bff; color: white; text-decoration: none; border-radius: 6px;">Generate a Password</a>
            <a href="save_password.php" style="display: inline-block; margin: 10px; padding: 10px 25px; background-color: #007bff; color: white; text-decoration: none; border-radius: 6px;">Save a Password</a>
            <a href="view_passwords.php" style="display: inline-block; margin: 10px; padding: 10px 25px; background-color: #007bff; color: white; text-decoration: none; border-radius: 6px;">View Saved Passwords</a>
        </div>
    </div>

</body>
</html>