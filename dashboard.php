<li?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username=htmlspecialchars($_SESSION['user']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .dashboard {
            width: 60%;
            margin: auto;
            padding-top: 50px;
        }
        h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <p>This is your dashboard. What would you like to do?</p>

        <ul>
            <li><a href="#">Generate a Password</a></li>
            <li><a href="#">Save a Password</a></li>
            <li><a href="#">View Saved Passwords</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a class="button" href="index.php">Back to Home</a></li>

        </ul>
    </div>
</body>
</html>
