<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profile</title>
</head>
<body>

<h2>My Profile</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>

<a href="edit_profile.php">Edit Profile</a>
<a href="user_dashboard.php">Back to Dashboard</a>

</body>
</html>
