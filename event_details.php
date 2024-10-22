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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4ebd0; /* Cream */
            color: #122620; /* Charcoal */
            font-family: 'Poppins', sans-serif; /* Poppins Font */
        }
        .navbar, .footer {
            background-color: #b68d40; /* Tan */
        }
        .navbar-brand {
            font-size: 2rem; /* Bigger Font */
            font-weight: 600;
            color: white !important;
        }
        .navbar-nav .nav-link {
            font-size: 1.2rem; /* Larger Font */
            font-weight: 500;
            color: white !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .navbar-nav .nav-link:hover {
            color: #d6ad60 !important; /* Gold color on hover */
            text-decoration: underline;
        }
        .btn-custom {
            background-color: #d6ad60; /* Gold */
            color: white;
        }
        h2, h3 {
            color: #122620; /* Charcoal */
        }
        .card {
            background-color: #f4ebd0; /* Cream */
            border: 1px solid #b68d40; /* Tan */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="user_dashboard.php">Event Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="view_profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Event Details -->
    <div class="container my-5">
        <div class="card p-4">
            <h2 class="card-title">Event Details: <?= htmlspecialchars($event['name']); ?></h2>
            <p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($event['time']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($event['description']); ?></p>

            <form method="post">
                <button type="submit" class="btn btn-custom">Register for this Event</button>
            </form>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <a href="user_dashboard.php" class="btn btn-outline-dark mt-3">Back to Events</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center py-3">
        <div class="container">
            <span>&copy; 2024 Event Dashboard</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
