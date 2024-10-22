<?php
session_start();
require 'db_config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

// Fetch registered events with additional information
$stmt = $pdo->prepare("SELECT events.*, registrations.full_name, registrations.email, registrations.phone_number, registrations.date_of_birth, registrations.address 
                       FROM registrations
                       JOIN events ON registrations.event_id = events.id
                       WHERE registrations.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$registered_events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Registered Events</title>
</head>
<body>

<h2>My Registered Events</h2>

<table border="1">
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
            <a href="event_details.php?event_id=<?= $event['id']; ?>">View Details</a> |
            <a href="view_registered_events.php?cancel_event_id=<?= $event['id']; ?>" onclick="return confirm('Are you sure you want to cancel this registration?')">Cancel</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="user_dashboard.php">Back to Dashboard</a>

</body>
</html>
