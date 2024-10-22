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

// Fetch event details for display
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die('Event not found.');
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];

    // Insert registration with additional information
    $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id, full_name, email, phone_number, date_of_birth, address) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $event_id, $full_name, $email, $phone_number, $date_of_birth, $address]);

    header("Location: view_registered_events.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Provide Additional Information</title>
</head>
<body>

<h2>Register for Event: <?= htmlspecialchars($event['name']); ?></h2>

<form method="post">
    <label>Full Name:</label>
    <input type="text" name="full_name" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Phone Number:</label>
    <input type="text" name="phone_number" required><br>

    <label>Date of Birth:</label>
    <input type="date" name="date_of_birth" required><br>

    <label>Home Address:</label>
    <textarea name="address" required></textarea><br>

    <button type="submit">Complete Registration</button>
</form>

<a href="user_dashboard.php">Cancel</a>

</body>
</html>
