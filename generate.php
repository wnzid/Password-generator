<?php
session_start();

require_once 'classes/passwordGenerator.php';
require_once 'config/db.php';

$generatedPassword='';
$error='';
$saveMessage='';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['generate'])) {
    $length=(int) $_POST['length'];
    $uppercase=(int) $_POST['uppercase'];
    $lowercase=(int) $_POST['lowercase'];
    $numbers=(int) $_POST['numbers'];
    $special=(int) $_POST['special'];

    $totalRequested=$uppercase+$lowercase+$numbers+$special;

    if ($totalRequested > $length) {
        $error="Error: Total characters selected ($totalRequested) exceeds total length ($length).";
    } else {
        $gen=new PasswordGenerator($length, $uppercase, $lowercase, $numbers, $special);
        $generatedPassword=$gen->generate();
    }
}

if (isset($_POST['save'])) {
    $platform=trim($_POST['platform']);
    $appUsername=trim($_POST['app_username']);
    $passwordToSave=$_POST['generated_password'];
    $owner=$_SESSION['user'];

    $db=new Database();
    $conn=$db->connect();

    $sql="INSERT INTO passwords (username, platform, platform_username, password_value)
            VALUES (:owner, :platform, :app_username, :password)";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':owner', $owner);
    $stmt->bindParam(':platform', $platform);
    $stmt->bindParam(':app_username', $appUsername);
    $stmt->bindParam(':password', $passwordToSave);

    if ($stmt->execute()) {
        $saveMessage="Password saved successfully!";
    } else {
        $saveMessage="Failed to save password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Password</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f8f8f8; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">

        <h2 style="text-align: center;">Generate a Password</h2>

        <form method="post" action="">
            <label>Total Length:</label>
            <input type="number" name="length" required style="width:100%; padding:10px; margin:10px 0;"><br>

            <label>Uppercase Letters:</label>
            <input type="number" name="uppercase" required style="width:100%; padding:10px; margin:10px 0;"><br>

            <label>Lowercase Letters:</label>
            <input type="number" name="lowercase" required style="width:100%; padding:10px; margin:10px 0;"><br>

            <label>Numbers:</label>
            <input type="number" name="numbers" required style="width:100%; padding:10px; margin:10px 0;"><br>

            <label>Special Characters:</label>
            <input type="number" name="special" required style="width:100%; padding:10px; margin:10px 0;"><br>

            <input type="submit" name="generate" value="Generate Password" style="width:100%; padding:12px; background-color:#007bff; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer;">
        </form>

        <?php if (!empty($error)): ?>
            <p style="color:red; font-weight:bold; margin-top: 20px;"><?php echo $error; ?></p>
        <?php elseif (!empty($generatedPassword)): ?>
            <h3 style="margin-top: 30px;">Generated Password:</h3>
            <p style="font-weight: bold;"><?php echo htmlspecialchars($generatedPassword); ?></p>

            <form method="post" action="" style="margin-top: 20px;">
                <input type="hidden" name="generated_password" value="<?php echo htmlspecialchars($generatedPassword); ?>">

                <label>Platform:</label>
                <input type="text" name="platform" required placeholder="e.g. Gmail" style="width:100%; padding:10px; margin:10px 0;"><br>

                <label>Username for Platform:</label>
                <input type="text" name="app_username" required placeholder="e.g. you@gmail.com" style="width:100%; padding:10px; margin:10px 0;"><br>

                <input type="submit" name="save" value="Save Password" style="width:100%; padding:12px; background-color:#28a745; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer;">
            </form>
        <?php endif; ?>

        <?php if (!empty($saveMessage)): ?>
            <p style="color: green; font-weight: bold; margin-top: 20px;"><?php echo $saveMessage; ?></p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" style="text-decoration: none; padding: 8px 20px; background-color: #6c757d; color: white; border-radius: 6px;">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>