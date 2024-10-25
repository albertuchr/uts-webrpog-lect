<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
if (!$event_id) {
    die('Event ID is missing.');
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die('Event not found.');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $event_id]);

    if ($stmt->rowCount() > 0) {
        $error = "You are already registered for this event!";
    } else {
        header("Location: event_registration_info.php?event_id=" . $event_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; 
            color: #b68d40; 
            font-family: 'Poppins', sans-serif; 
        }
        h2 {
            font-family: 'Playfair Display', serif;
            color: #b68d40;
            text-align: center;
            margin-top: 20px;
            font-size: 2.5rem;
        }

        .container {
            margin-top: 30px;
            background-color: #f4ebd0;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }

        .btn-register {
            background-color: #d6ad60;
            color: #122620;
            margin-top: 20px;
            width: 100%;
            font-size: 1.2rem;
        }
        .btn-register:hover {
            opacity: 0.85; 
        }
        .footer {
            color: #b68d40; 
            text-align: center;
            padding: 10px 0;
        }
        a {
            color: #d6ad60;
        }
        a:hover {
            color: #f4ebd0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Event Details: <?= htmlspecialchars($event['name']); ?></h2>
    <p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($event['time']); ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($event['description']); ?></p>

    <form method="post">
        <button type="submit" class="btn btn-lg btn-register">Register for this Event</button>
    </form>

    <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>

    <div class="text-center mt-4">
        <a href="user_dashboard.php" class="btn btn-secondary">Back to Events</a>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <span>&copy; 2024 My Event Dashboard</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
