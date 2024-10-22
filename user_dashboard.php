<?php
session_start();
require 'db_config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM events WHERE status = 'open'");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Events</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4ebd0; /* Cream */
            color: #122620; /* Charcoal */
        }
        .navbar, .footer {
            background-color: #b68d40; /* Tan */
        }
        .btn-custom {
            background-color: #d6ad60; /* Gold */
            color: white;
        }
        .table th {
            background-color: #b68d40; 
            color: white;
        }
        .table td {
            background-color: #f4ebd0; /* Cream */
        }
        h2, h3 {
            color: #122620; /* Charcoal */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Event Dashboard</a>
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

    <div class="container my-5">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
        <h3>Available Events</h3>

        <!-- Events Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['name']); ?></td>
                    <td><?= htmlspecialchars($event['date']); ?></td>
                    <td><?= htmlspecialchars($event['time']); ?></td>
                    <td><?= htmlspecialchars($event['location']); ?></td>
                    <td><?= htmlspecialchars($event['description']); ?></td>
                    <td>
                        <a href="event_details.php?event_id=<?= $event['id']; ?>" class="btn btn-custom btn-sm">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <h3>My Registered Events</h3>
        <a href="view_registered_events.php" class="btn btn-custom">View Events I've Registered</a>

        <br><br>
        <h3>Profile Management</h3>
        <a href="view_profile.php" class="btn btn-outline-dark">View Profile</a>
        <a href="edit_profile.php" class="btn btn-outline-dark">Edit Profile</a>

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
