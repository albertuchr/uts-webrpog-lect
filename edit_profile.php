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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password']; // Update only if a new password is provided

    // Update user details
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
    $stmt->execute([$name, $email, $password, $_SESSION['user_id']]);

    $_SESSION['user_name'] = $name; // Update session name
    header("Location: view_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>

<h2>Edit Profile</h2>
<form method="post">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required><br>
    <label>Password (leave blank to keep current password):</label>
    <input type="password" name="password"><br>
    <button type="submit">Update Profile</button>
</form>

<a href="view_profile.php">Back to Profile</a>

</body>
</html>
