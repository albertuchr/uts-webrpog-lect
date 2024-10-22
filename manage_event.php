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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; /* Charcoal */
            color: #b68d40; /* Cream for text */
            font-family: 'Garamond', serif; /* Font style set to Garamond */
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px; /* Rounded corners for the container */
            background-color: rgba(18, 38, 32, 0.9); /* Slightly transparent background for contrast */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }

        h2 {
            text-align: center; /* Center titles */
            font-size: 2.5em; /* Larger font size for main title */
            font-weight: bold; /* Bold for emphasis */
            margin-bottom: 20px; /* Space below title */
        }

        label {
            font-weight: bold; /* Bold labels for emphasis */
        }

        input, textarea, select {
            background-color: #f4ebd0; /* Cream for input fields */
            color: #122620; /* Charcoal for text */
            border: 1px solid #d6ad60; /* Gold border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding for input fields */
            width: 100%; /* Full width for input fields */
            margin-bottom: 15px; /* Space below input fields */
            font-size: 1em; /* Font size for input fields */
        }

        button {
            background-color: #d6ad60; /* Gold */
            color: #122620; /* Charcoal */
            border: none;
            padding: 10px 15px; /* Padding for button */
            margin-top: 10px; /* Space above button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Animation for button hover */
        }

        button:hover {
            background-color: #b68d40; /* Tan on hover */
            color: white; /* Change hover text to white */
        }

        .error {
            color: red; /* Error message color */
            text-align: center; /* Center error message */
            margin-bottom: 15px; /* Space below error message */
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action == 'edit'): ?>
            <h2>Edit Event: <?= htmlspecialchars($event['name']); ?></h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($event['name']); ?>" required>
                <label>Date:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($event['date']); ?>" required>
                <label>Time:</label>
                <input type="time" name="time" value="<?= htmlspecialchars($event['time']); ?>" required>
                <label>Location:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required>
                <label>Description:</label>
                <textarea name="description"><?= htmlspecialchars($event['description']); ?></textarea>
                <label>Max Participants:</label>
                <input type="number" name="max_participants" value="<?= htmlspecialchars($event['max_participants']); ?>" required>
                <label>Status:</label>
                <select name="status">
                    <option value="open" <?= $event['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                    <option value="closed" <?= $event['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                    <option value="canceled" <?= $event['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit">Update Event</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
