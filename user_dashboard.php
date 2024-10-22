<?php
session_start();
require 'db_config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

// Fetch all events available for registration
$stmt = $pdo->query("SELECT * FROM events WHERE status = 'open'");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Events</title>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
<h3>Available Events</h3>

<table border="1">
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Details</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= htmlspecialchars($event['name']); ?></td>
        <td><?= htmlspecialchars($event['date']); ?></td>
        <td><?= htmlspecialchars($event['time']); ?></td>
        <td><?= htmlspecialchars($event['location']); ?></td>
        <td><?= htmlspecialchars($event['description']); ?></td>
        <td>
            <a href="event_details.php?event_id=<?= $event['id']; ?>">View Details</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br><br>
<h3>My Registered Events</h3>
<a href="view_registered_events.php">View Events I've Registered</a>

<br><br>
<h3>Profile Management</h3>
<a href="view_profile.php">View Profile</a> |
<a href="edit_profile.php">Edit Profile</a>

<br><br>
<a href="user_logout.php">Logout</a>

</body>
</html>
