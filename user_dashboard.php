<?php
session_start();
require 'db_config.php'; 

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; 
            color: #f4ebd0; 
            font-family: 'Poppins', sans-serif; 
        }
        vbar, .footer {
            background-color: #122620;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #d6ad60 !important; 
        }
        .navbar-nav .nav-link {
            font-size: 1.2rem;
            font-weight: 500;
            color: #f4ebd0 !important;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .navbar-nav .nav-link:hover {.na
            color: #d6ad60 !important;
            text-decoration: underline;
        }
        h2, h3 {
            font-family: 'Playfair Display', serif; 
            color: #f4ebd0; 
        }
        h2 {
            font-size: 2.5rem; 
        }
        h3 {
            font-size: 1.8rem; 
        }
        .btn-custom {
            background-color: #d6ad60; 
            color: #122620; 
        }
        .btn-outline-dark {
            border-color: #f4ebd0; 
            color: #f4ebd0; 
        }
        .btn-outline-dark:hover {
            background-color: #f4ebd0; 
            color: #122620; 
        }
        .table {
    border-collapse: collapse; 
    border-spacing: 0; 
    border-radius: 10px; 
    overflow: hidden; 
    border: 2px solid #b68d40; 
}

        .table th {
            background-color: #d6ad60; 
            color: #122620; 
        }
        .table td {
            background-color: #f4ebd0;
            color: #122620;
        }

        .footer {
            color: #f4ebd0;
        }
        a {
            color: #d6ad60;
        }
        a:hover {
            color: #f4ebd0;
        }
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

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Image</th>
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

    <footer class="footer text-center py-3">
        <div class="container">
            <span>&copy; 2024 Event Dashboard</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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
