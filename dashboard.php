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
<body style="font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f9f9f9; padding: 40px;">

    <table width="100%" cellpadding="10">
        <tr style="background-color: #ffffff;">
            <td align="center">
                <a href="dashboard.php">Dashboard</a> |
                <a href="generate.php">Generate</a> |
                <a href="save_password.php">Save</a> |
                <a href="view_passwords.php">Saved</a> |
                <a href="logout.php">Logout</a>
            </td>
        </tr>
    </table>

    <div style="max-width: 600px; margin: 40px auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc;">
        <h2 style="text-align:center;">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p style="text-align:center;">This is your dashboard. What would you like to do?</p>

        <table width="100%" cellpadding="10" style="margin-top: 30px;">
            <tr>
                <td align="center"><a href="generate.php">Generate a Password</a></td>
                <td align="center"><a href="save_password.php">Save a Password</a></td>
            </tr>
            <tr>
                <td align="center"><a href="view_passwords.php">View Saved Passwords</a></td>
                <td align="center"><a href="index.php">Back to Home</a></td>
            </tr>
        </table>
    </div>

</body>
</html>