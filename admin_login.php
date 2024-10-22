<?php
session_start();

// Hardcoded admin credentials
$admin_username = 'admin';
$admin_password = 'admin123';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password
    if ($username === $admin_username && $password === $admin_password) {
        // Successful login, set session and redirect to dashboard
        $_SESSION['admin_id'] = 1; // Assigning a dummy admin ID
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php"); // Redirect to the dashboard
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
