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
        /* Palet Warna */
        :root {
            --tan: #d2b48c;
            --cream: #f5f5dc;
            --charcoal: #36454f;
            --gold: #d4af37;
        }

        body {
            background-color: var(--cream);
            font-family: 'Arial', sans-serif;
            color: var(--charcoal);
            margin: 0;
            padding: 0;
        }

        .profile-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--tan);
        }

        .profile-card {
            width: 100%;
            max-width: 700px;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        label {
            color: var(--gold);
            font-weight: bold;
        }

        input.form-control {
            border: 1px solid var(--charcoal);
            border-radius: 6px;
            padding: 10px;
            font-size: 1rem;
        }

        .btn-save {
            background-color: var(--gold);
            border: none;
            border-radius: 50px;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-save:hover {
            background-color: var(--charcoal);
        }

        @media (max-width: 768px) {
            .profile-card {
                padding: 20px;
            }

            .btn-save {
                padding: 10px 20px;
                font-size: 14px;
            }
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
