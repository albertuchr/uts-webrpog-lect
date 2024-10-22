<?php
session_start();
require 'db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Get the event ID and action from the query parameters
$event_id = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$event_id || !$action) {
    die('Event ID or action is missing.');
}

// Handle event deletion
if ($action == 'delete') {
    // Delete the event
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$event_id]);

    // Redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit;
}

// Handle event editing (fetch the event details first)
if ($action == 'edit') {
    // Fetch event details
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        die('Event not found.');
    }

    // Check if form is submitted for updating the event
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $max_participants = $_POST['max_participants'];
        $status = $_POST['status'];

        // Update the event in the database
        $stmt = $pdo->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $date, $time, $location, $description, $max_participants, $status, $event_id]);

        // Redirect back to admin dashboard after successful update
        header("Location: admin_dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Event</title>
</head>
<body>
    <?php if ($action == 'edit'): ?>
    <h2>Edit Event: <?= htmlspecialchars($event['name']); ?></h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($event['name']); ?>" required><br>
        <label>Date:</label>
        <input type="date" name="date" value="<?= htmlspecialchars($event['date']); ?>" required><br>
        <label>Time:</label>
        <input type="time" name="time" value="<?= htmlspecialchars($event['time']); ?>" required><br>
        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required><br>
        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($event['description']); ?></textarea><br>
        <label>Max Participants:</label>
        <input type="number" name="max_participants" value="<?= htmlspecialchars($event['max_participants']); ?>" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="open" <?= $event['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
            <option value="closed" <?= $event['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
            <option value="canceled" <?= $event['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
        </select><br>
        <button type="submit">Update Event</button>
    </form>
    <?php endif; ?>
</body>
</html>
