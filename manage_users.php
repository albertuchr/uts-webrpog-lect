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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; /* Charcoal */
            color: #f4ebd0; /* Cream */
            font-family: 'Garamond', serif;
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px;
            background-color: rgba(18, 38, 32, 0.9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5em;
            font-weight: bold;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th {
            padding: 12px;
            text-align: left;
            border: 1px solid #f4ebd0;
            background-color: #b68d40;
            color: #122620;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #f4ebd0;
            background-color: #f4ebd0;
            color: #122620;
        }

        a {
            text-decoration: none;
            color: #d6ad60; /* Gold */
            font-weight: bold;
        }

        a:hover {
            color: #b68d40; /* Darker gold on hover */
        }

        .btn-back {
            margin-top: 20px;
            display: block;
            text-align: center;
            background-color: #d6ad60;
            color: #122620;
            border: none;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #b68d40;
            color: white;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            h2 {
                font-size: 2em;
            }

            table {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Registered Users</h2>

        <table>
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
                        <a href="manage_users.php?id=<?= $user['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>
</body>

</html>
