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

    $sql="DELETE FROM passwords WHERE id=:id AND username=:username";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id', $idToDelete);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

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
            width: 80%;
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

    <p style="text-align:center;"><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>