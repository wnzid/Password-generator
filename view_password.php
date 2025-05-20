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

    $sql="DELETE FROM passwords WHERE id=:id AND username = :username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id', $idToDelete);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

if (isset($_POST['update'])) {
    $updateId=(int) $_POST['id'];
    $newPlatform=trim($_POST['platform']);
    $newPassword=trim($_POST['password']);

    $sql="UPDATE passwords 
            SET platform=:platform, password_value = :password 
            WHERE id=:id AND username = :username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':platform', $newPlatform);
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':id', $updateId);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;

$sql="SELECT id, platform, password_value, created_at 
        FROM passwords 
        WHERE username=:username 
        ORDER BY created_at DESC";

$stmt=$conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();

$passwords=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Saved Passwords</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #ddd;
        }
        a.delete-link {
            color: red;
            text-decoration: none;
        }
        form {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #aaa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Your Saved Passwords</h2>

    <?php if (count($passwords) > 0): ?>
        <table>
            <tr>
                <th>Platform</th>
                <th>Password</th>
                <th>Date Saved</th>
                <th>Action</th>
            </tr>
            <?php foreach ($passwords as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['platform']); ?></td>
                    <td><?php echo htmlspecialchars($row['password_value']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                        <a class="delete-link" href="?delete=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this password?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No passwords saved yet.</p>
    <?php endif; ?>

    <?php if ($editingId): ?>
        <?php
        $stmt=$conn->prepare("SELECT * FROM passwords WHERE id = :id AND username = :username");
        $stmt->bindParam(':id', $editingId);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $editRow=$stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <?php if ($editRow): ?>
            <hr>
            <h3 style="text-align:center;">Edit Saved Password</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">

                <label>Platform:</label><br>
                <input type="text" name="platform" value="<?php echo htmlspecialchars($editRow['platform']); ?>" required><br><br>

                <label>Password:</label><br>
                <input type="text" name="password" value="<?php echo htmlspecialchars($editRow['password_value']); ?>" required><br><br>

                <input type="submit" name="update" value="Update">
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <p style="text-align:center;"><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>