<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
$users = $stmt->fetchAll();

// Handle user deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $user_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: manage_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Registered Users</h2>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['name']); ?></td>
        <td><?= htmlspecialchars($user['email']); ?></td>
        <td>
            <a href="manage_users.php?id=<?= $user['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
