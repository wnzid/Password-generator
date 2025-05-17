<?php
require_once 'classes/passwordGenerator.php';

$generatedPassword = '';

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $length=(int) $_POST['length'];
    $uppercase=(int) $_POST['uppercase'];
    $lowercase=(int) $_POST['lowercase'];
    $numbers=(int) $_POST['numbers'];
    $special=(int) $_POST['special'];

    $gen=new PasswordGenerator($length, $uppercase, $lowercase, $numbers, $special);
    $generatedPassword=$gen->generate();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Generator</title>
</head>
<body>
    <h2>Password Generator</h2>
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

        <input type="submit" value="Generate">
    </form>

    <?php if (!empty($generatedPassword)): ?>
        <h3>Generated Password:</h3>
        <p style="font-weight: bold;"><?php echo htmlspecialchars($generatedPassword); ?></p>
    <?php endif; ?>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
