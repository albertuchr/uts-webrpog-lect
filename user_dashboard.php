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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; /* Dark Charcoal Background */
            color: #f4ebd0; /* Cream for Text */
            font-family: 'Poppins', sans-serif; /* Poppins for general text */
        }
        .navbar, .footer {
            background-color: #122620; /* Match background color */
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif; /* Stylish serif font for logo */
            font-size: 2rem;
            font-weight: 700;
            color: #d6ad60 !important; /* Gold color for branding */
        }
        .navbar-nav .nav-link {
            font-size: 1.2rem;
            font-weight: 500;
            color: #f4ebd0 !important; /* Cream for navbar links */
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .navbar-nav .nav-link:hover {
            color: #d6ad60 !important; /* Gold on hover */
            text-decoration: underline;
        }
        h2, h3 {
            font-family: 'Playfair Display', serif; /* Stylish serif font for headers */
            color: #f4ebd0; /* Cream for headings */
        }
        h2 {
            font-size: 2.5rem; /* Large heading for welcome message */
        }
        h3 {
            font-size: 1.8rem; /* Slightly smaller for subheadings */
        }
        .btn-custom {
            background-color: #d6ad60; /* Gold for buttons */
            color: #122620; /* Dark text on gold buttons */
        }
        .btn-outline-dark {
            border-color: #f4ebd0; /* Cream border for outline buttons */
            color: #f4ebd0; /* Cream text */
        }
        .btn-outline-dark:hover {
            background-color: #f4ebd0; /* Cream background on hover */
            color: #122620; /* Dark text on hover */
        }
        .table th {
            background-color: #d6ad60; /* Gold for table headers */
            color: #122620; /* Dark text on gold */
        }
        .table td {
            background-color: #ffffff; /* White background for table data cells */
            color: #122620; /* Dark text for readability */
        }
        .footer {
            color: #f4ebd0; /* Cream text for footer */
        }
        a {
            color: #d6ad60; /* Gold links */
        }
        a:hover {
            color: #f4ebd0; /* Cream on hover */
        }
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: #d6ad60;
            cursor: pointer;
        }
        .caption {
            text-align: center;
            color: #f4ebd0;
            padding: 10px;
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
                    <th>Image</th> <!-- New column for event image -->
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
                    <td>
                        <!-- Display event image if available -->
                        <?php if ($event['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($event['image']); ?>" alt="Event Image" style="width: 100px; cursor: pointer;" onclick="openModal('uploads/<?= htmlspecialchars($event['image']); ?>')">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($event['description']); ?></td>
                    <td>
                        <a href="event_details.php?event_id=<?= $event['id']; ?>" class="btn btn-custom btn-sm">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal for image zoom -->
        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImage">
            <div class="caption" id="caption"></div>
        </div>

        <br>
        <h3>My Registered Events</h3>
        <a href="view_registered_events.php" class="btn btn-custom">View Events I've Registered</a>

        <br><br>
    </div>

    <!-- Footer -->
    <footer class="footer text-center py-3">
        <div class="container">
            <span>&copy; 2024 Event Dashboard</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal JavaScript -->
    <script>
        function openModal(imageUrl) {
            var modal = document.getElementById("imageModal");
            var modalImage = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImage.src = imageUrl;
        }

        function closeModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>

</body>
</html>
