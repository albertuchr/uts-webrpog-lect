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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #122620;
        }
        h2 {
            color: #B68D40;
            text-align: center;
            margin-top: 20px;
            font-family: garamond;
            font-size: 50px;
        }

        table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #d6ad60; /* Gold background for table headers */
            color: white;
            font-family: futura;
        }
        .table td {
            background-color: #f4ebd0; /* White background for table data */
            color: #122620;
        }
        .btn-delete {
            background-color: #B68D40; /* Tan color */
            color: white;
        }
        .btn-delete:hover {
            opacity: 0.85;
        }
        .table-wrapper {
            padding: 20px;
            margin: 20px auto;
            max-width: 90%;
        }
    </style>
</head>
<body>

<h2>Manage Registered Users</h2>

<div class="table-wrapper">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td>
                    <a href="manage_users.php?id=<?= $user['id']; ?>&action=delete" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center">
    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>