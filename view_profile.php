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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Palet Warna Baru */
        :root {
            --tan: #d2b48c;
            --cream: #f5f5dc;
            --charcoal: #36454f;
            --gold: #d4af37;
        }

        body {
            background-color: var(--cream);
            font-family: 'Roboto', sans-serif;
            color: var(--charcoal);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .profile-container {
            width: 90%;
            max-width: 1000px;
            background-color: var(--tan);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 40px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--gold);
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-info {
            background-color: var(--cream);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .profile-info p {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .profile-info p strong {
            color: var(--charcoal);
        }

        .event-history {
            background-color: var(--cream);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .event-history h4 {
            font-size: 1.8rem;
            color: var(--gold);
            margin-bottom: 15px;
        }

        .event-history ul {
            padding-left: 0;
            list-style: none;
        }

        .event-history ul li {
            padding: 10px;
            background-color: var(--tan);
            color: var(--charcoal);
            margin-bottom: 10px;
            border-left: 5px solid var(--gold);
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .event-history ul li:hover {
            transform: translateX(10px);
        }

        .profile-actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn-custom {
            padding: 10px 25px;
            border-radius: 30px;
            font-size: 1.1rem;
            margin-right: 10px;
            border: none;
            background-color: var(--gold);
            color: var(--cream);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--charcoal);
            color: var(--cream);
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: var(--charcoal);
            color: var(--cream);
            padding: 10px 25px;
            border-radius: 30px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: var(--gold);
            color: var(--charcoal);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 20px;
            }

            .profile-header {
                font-size: 2rem;
            }

            .profile-info p {
                font-size: 1rem;
            }

            .event-history h4 {
                font-size: 1.5rem;
            }

            .btn-custom, .btn-secondary {
                font-size: 1rem;
                padding: 8px 20px;
            }
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2 class="profile-header">User Profile</h2>
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
        <a href="edit_profile.php" class="btn btn-custom">Edit Profile</a>
        <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
