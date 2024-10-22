<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

// Handle event cancellation
if (isset($_GET['cancel_event_id'])) {
    $cancel_event_id = $_GET['cancel_event_id'];

    // Delete the registration for the user and event
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
    $stmt->execute([$cancel_event_id, $_SESSION['user_id']]);

    // Redirect back to the registered events page to avoid re-submission
    header("Location: view_registered_events.php");
    exit;
}

// Fetch the registered events for the current user
$stmt = $pdo->prepare("SELECT events.*, registrations.full_name, registrations.email, registrations.phone_number, registrations.date_of_birth, registrations.address 
                       FROM registrations
                       JOIN events ON registrations.event_id = events.id
                       WHERE registrations.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$registered_events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registered Events</title>
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
            background-color: #d6ad60;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th {
            background-color: #D6AD60; /* Gold color */
            color: white;
            font-family: futura;
        }
        td {
            color: #122620;
        }
        .btn-view, .btn-cancel {
            margin-right: 5px;
        }
        .btn-view {
            background-color: #B68D40; /* Tan color */
            color: white;
        }
        .btn-cancel {
            background-color: #D6AD60; /* Gold color */
            color: white;
        }
        .btn-view:hover, .btn-cancel:hover {
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

<h2>My Registered Events</h2>

<div class="table-wrapper">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registered_events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['name']); ?></td>
                <td><?= htmlspecialchars($event['date']); ?></td>
                <td><?= htmlspecialchars($event['time']); ?></td>
                <td><?= htmlspecialchars($event['location']); ?></td>
                <td><?= htmlspecialchars($event['full_name']); ?></td>
                <td><?= htmlspecialchars($event['email']); ?></td>
                <td><?= htmlspecialchars($event['phone_number']); ?></td>
                <td><?= htmlspecialchars($event['date_of_birth']); ?></td>
                <td><?= htmlspecialchars($event['address']); ?></td>
                <td>
                    <a href="event_details.php?event_id=<?= $event['id']; ?>" class="btn btn-sm btn-view">View Details</a>
                    <a href="view_registered_events.php?cancel_event_id=<?= $event['id']; ?>" class="btn btn-sm btn-cancel" onclick="return confirm('Are you sure you want to cancel this registration?')">Cancel</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center">
    <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
