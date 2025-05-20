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

    if ($totalRequested>$length) {
        $error="Error: Total characters selected ($totalRequested) exceeds total length ($length).";
    } else {
        $gen=new PasswordGenerator($length, $uppercase, $lowercase, $numbers, $special);
        $generatedPassword=$gen->generate();
    }
}

if (isset($_POST['save'])) {
    $platform=trim($_POST['platform']);
    $passwordToSave=$_POST['generated_password'];

    $db=new Database();
    $conn=$db->connect();

    $sql="INSERT INTO passwords (username, platform, password_value) 
            VALUES (:username, :platform, :password)";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':username', $_SESSION['user']);
    $stmt->bindParam(':platform', $platform);
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
            <input type="number" name="length" required><br><br>

            <label>Uppercase Letters:</label>
            <input type="number" name="uppercase" required><br><br>

            <label>Lowercase Letters:</label>
            <input type="number" name="lowercase" required><br><br>

            <label>Numbers:</label>
            <input type="number" name="numbers" required><br><br>

            <label>Special Characters:</label>
            <input type="number" name="special" required><br><br>

            <input type="submit" name="generate" value="Generate Password">
        </form>

        <?php if (!empty($error)): ?>
            <p style="color:red; font-weight:bold;"><?php echo $error; ?></p>
        <?php elseif (!empty($generatedPassword)): ?>
            <h3>Generated Password:</h3>
            <p style="font-weight: bold;"><?php echo htmlspecialchars($generatedPassword); ?></p>

            <form method="post" action="">
                <input type="hidden" name="generated_password" value="<?php echo htmlspecialchars($generatedPassword); ?>">
                <label>Platform (e.g. Gmail, Facebook):</label>
                <input type="text" name="platform" required><br><br>
                <input type="submit" name="save" value="Save Password">
            </form>
        <?php endif; ?>

        <?php if (!empty($saveMessage)): ?>
            <p style="color: green; font-weight: bold;"><?php echo $saveMessage; ?></p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" style="text-decoration: none; padding: 8px 20px; background-color: #6c757d; color: white; border-radius: 6px;">← Back to Dashboard</a>
        </div>

    </div>
</body>
</html>