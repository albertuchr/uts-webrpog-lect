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
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $postal_code = $_POST['postal_code'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];

    // Update user details
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, location = ?, postal_code = ?, password = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $location, $postal_code, $password, $_SESSION['user_id']]);

    $_SESSION['user_name'] = $name; // Update session name
    header("Location: view_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-card {
            width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .btn-save {
            background-color: #ff6b6b;
            border: none;
            border-radius: 50px;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
        }
        .btn-save:hover {
            background-color: #ff5252;
        }
    </style>
</head>
<body>

<div class="container profile-wrapper">
    <div class="profile-card">
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($user['location'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" class="form-control" value="<?= htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
