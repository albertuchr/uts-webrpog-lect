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

// Fetch event registration history
$reg_stmt = $pdo->prepare("SELECT events.name, events.date, events.location 
                           FROM registrations 
                           JOIN events ON registrations.event_id = events.id 
                           WHERE registrations.user_id = ?");
$reg_stmt->execute([$_SESSION['user_id']]);
$registrations = $reg_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            margin-top: 50px;
        }
        .profile-card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .profile-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #343a40;
        }
        .profile-info p {
            margin-bottom: 10px;
        }
        .event-history {
            margin-top: 20px;
        }
        .btn-custom {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container profile-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <h2 class="profile-header">My Profile</h2>
                <div class="profile-info">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($user['location'] ?? 'Not provided'); ?></p>
                    <p><strong>Postal Code:</strong> <?= htmlspecialchars($user['postal_code'] ?? 'Not provided'); ?></p>
                </div>
                
                <!-- Event Registration History -->
                <div class="event-history">
                    <h4>Event Registration History</h4>
                    <?php if ($registrations): ?>
                        <ul>
                            <?php foreach ($registrations as $event): ?>
                                <li><?= htmlspecialchars($event['name']); ?> - <?= htmlspecialchars($event['date']); ?>, <?= htmlspecialchars($event['location']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No event registrations found.</p>
                    <?php endif; ?>
                </div>

                <!-- Profile Actions -->
                <div class="profile-actions">
                    <a href="edit_profile.php" class="btn btn-primary btn-custom">Edit Profile</a>
                    <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
