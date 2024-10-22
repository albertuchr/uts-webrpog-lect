<?php
session_start();
require 'db_config.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all events and their registrant counts
$stmt = $pdo->query("SELECT events.*, COUNT(registrations.id) AS total_registrants
                     FROM events
                     LEFT JOIN registrations ON events.id = registrations.event_id
                     GROUP BY events.id");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Events</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Welcome, Admin</h2>
<h3>Available Events</h3>

<table>
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Max Participants</th>
        <th>Status</th>
        <th>Registrants</th> <!-- Show total registrants -->
        <th>Actions</th>
    </tr>

    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= htmlspecialchars($event['name']); ?></td>
        <td><?= htmlspecialchars($event['date']); ?></td>
        <td><?= htmlspecialchars($event['time']); ?></td>
        <td><?= htmlspecialchars($event['location']); ?></td>
        <td><?= htmlspecialchars($event['max_participants']); ?></td>
        <td><?= htmlspecialchars($event['status']); ?></td>
        <td><?= htmlspecialchars($event['total_registrants']); ?></td> <!-- Display registrants count -->
        <td>
            <a href="manage_event.php?id=<?= $event['id']; ?>&action=edit">Edit</a> |
            <a href="manage_event.php?id=<?= $event['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a> |
            <a href="view_registrants.php?event_id=<?= $event['id']; ?>">View Registrants</a> <!-- Link to view registrants -->
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Add a link to create a new event -->
<br>
<a href="create_event.php">Create New Event</a>
<br><br>

<!-- Add a link to manage users -->
<h3>Manage Users</h3>
<a href="manage_users.php">View All Users</a>
<br><br>

<!-- Logout link -->
<a href="admin_logout.php">Logout</a>

</body>
</html>
