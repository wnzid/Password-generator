<?php
require_once 'classes/User.php';
require_once 'config/db.php';

$message='';

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $db=new Database();
    $conn=$db->connect();

    $user=new User($_POST['username'], $_POST['password'], $conn);

    if ($user->validate()) {
        $result=$user->save();

        if ($result===true) {
            $message="User registered successfully!";
        } elseif ($result==="exists") {
            $message="Username already taken. Try another.";
        } else {
            $message="Something went wrong during registration.";
        }
    } else {
        $message="Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">

    <div style="max-width: 400px; margin: 80px auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h3 style="text-align: center;">Sign Up</h3>

        <form method="post" action="signup.php" style="margin-top: 30px;">
            <label>Username:</label>
            <input type="text" name="username" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <label>Password:</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

            <input type="submit" value="Sign Up" style="width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
        </form>

        <?php if (!empty($message)): ?>
            <p style="margin-top: 20px; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <p style="text-align: center; margin-top: 20px;">
            Already have an account?
            <a href="login.php" style="text-decoration: none; color: #007bff;">Login here</a>
        </p>
    </div>

</body>
</html>