<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$db=new Database();
$conn=$db->connect();
$username=$_SESSION['user'];

if (isset($_GET['delete'])) {
    $idToDelete=(int) $_GET['delete'];

    $sql="DELETE FROM passwords WHERE id = :id AND username = :username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id', $idToDelete);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

if (isset($_POST['update'])) {
    $updateId = (int) $_POST['id'];
    $newPlatform = trim($_POST['platform']);
    $newPlatformUsername = trim($_POST['platform_username']);
    $newPassword = trim($_POST['password']);

    $sql = "UPDATE passwords 
            SET platform = :platform, platform_username = :platform_username, password_value = :password 
            WHERE id = :id AND username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':platform', $newPlatform);
    $stmt->bindParam(':platform_username', $newPlatformUsername);
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':id', $updateId);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;

$sql = "SELECT id, platform, platform_username, password_value, created_at 
        FROM passwords 
        WHERE username = :username 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();

$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Saved Passwords</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">

    <div style="max-width: 900px; margin: auto; background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h2 style="text-align:center;">Your Saved Passwords</h2>

        <?php if (count($passwords) > 0): ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
                <tr style="background-color: #f3f3f3;">
                    <th style="padding: 10px; border: 1px solid #ccc;">Platform</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Username</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Password</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Date Saved</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Action</th>
                </tr>
                <?php foreach ($passwords as $row): ?>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?php echo htmlspecialchars($row['platform']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?php echo htmlspecialchars($row['platform_username']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?php echo htmlspecialchars($row['password_value']); ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?php echo $row['created_at']; ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this password?');" style="color:red;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center; margin-top: 20px;">No passwords saved yet.</p>
        <?php endif; ?>

        <?php if ($editingId): ?>
            <?php
            $stmt = $conn->prepare("SELECT * FROM passwords WHERE id = :id AND username = :username");
            $stmt->bindParam(':id', $editingId);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <?php if ($editRow): ?>
                <hr style="margin-top: 40px;">
                <h3 style="text-align:center;">Edit Saved Password</h3>
                <form method="post" action="" style="max-width: 500px; margin: 30px auto;">
                    <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">

                    <label>Platform:</label><br>
                    <input type="text" name="platform" value="<?php echo htmlspecialchars($editRow['platform']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

                    <label>Username for Platform:</label><br>
                    <input type="text" name="platform_username" value="<?php echo htmlspecialchars($editRow['platform_username']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

                    <label>Password:</label><br>
                    <input type="text" name="password" value="<?php echo htmlspecialchars($editRow['password_value']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;"><br>

                    <input type="submit" name="update" value="Update" style="width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 40px;">
            <a href="dashboard.php" style="text-decoration: none; padding: 8px 20px; background-color: #6c757d; color: white; border-radius: 6px;">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>