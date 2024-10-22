<?php
session_start();
require 'db_config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
if (!$event_id) {
    die('Event ID is missing.');
}

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die('Event not found.');
}

// Check if user is already registered for the event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $event_id]);

    if ($stmt->rowCount() > 0) {
        $error = "You are already registered for this event!";
    } else {
        // Redirect to the additional info page
        header("Location: event_registration_info.php?event_id=" . $event_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
</head>
<body>

<h2>Event Details: <?= htmlspecialchars($event['name']); ?></h2>
<p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
<p><strong>Time:</strong> <?= htmlspecialchars($event['time']); ?></p>
<p><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
<p><strong>Description:</strong> <?= htmlspecialchars($event['description']); ?></p>

<form method="post">
    <button type="submit">Register for this Event</button>
</form>

<?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

<a href="user_dashboard.php">Back to Events</a>

</body>
</html>
