<?php
require_once 'classes/User.php';
require_once 'config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $db = new Database();
    $conn = $db->connect();

    $user = new User($_POST['username'], $_POST['password'], $conn);

    if ($user->validate()) {
        if ($user->save()) {
            $message="User registered and saved to database!";
        } else {
            $message="Error saving user (username might already exist).";
        }
    } else {
        $message="Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup Page</title>
</head>
<body>
    <h2>User Signup</h2>
    <form method="post" action="signup.php">
        <label>Username:</label><br>
        <input type="text" name="username"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <input type="submit" value="Sign Up">
    </form>

    <p style="color:green;"><?php echo $message; ?></p>
</body>
</html>