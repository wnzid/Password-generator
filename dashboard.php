<?php
session_start();

$pageTitle="Dashboard";
include 'views/header.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

include 'views/footer.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
        }
        .card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
        }
        a.button {
            display: inline-block;
            margin: 10px 5px 0 0;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>This is your dashboard. What would you like to do?</p>

        <a class="button" href="generate.php">Generate a Password</a>
        <a class="button" href="save_password.php">Save a Password</a>
        <a class="button" href="view_password.php">View Saved Passwords</a>
        <a class="button" href="logout.php">Logout</a>
        <a class="button" href="index.php">Back to Home</a>
    </div>
</body>
</html>