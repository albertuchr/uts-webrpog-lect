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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Events</title>
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

        img {
            max-width: 100px;
            height: auto;
            cursor: pointer; /* Change cursor to pointer to indicate it's clickable */
        }

        /* Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.8); /* Black background with opacity */
        }

        /* Modal Content (image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%; /* Set default width */
            max-width: 700px; /* Max width */
        }

        /* Caption for the modal image */
        .caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
        }

        /* Close button */
        .close {
            position: absolute;
            top: 30px;
            right: 50px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover, .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Animation */
        .modal-content, .caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {transform: scale(0)}
            to {transform: scale(1)}
        }

        @keyframes zoom {
            from {transform: scale(0)}
            to {transform: scale(1)}
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

<table>
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Image</th> <!-- New column for event image -->
        <th>Details</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= htmlspecialchars($event['name']); ?></td>
        <td><?= htmlspecialchars($event['date']); ?></td>
        <td><?= htmlspecialchars($event['time']); ?></td>
        <td><?= htmlspecialchars($event['location']); ?></td>
        <td>
            <!-- Display event image if available -->
            <?php if ($event['image']): ?>
                <img src="uploads/<?= htmlspecialchars($event['image']); ?>" alt="Event Image" onclick="openModal('uploads/<?= htmlspecialchars($event['image']); ?>')">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($event['description']); ?></td>
        <td>
            <a href="event_details.php?event_id=<?= $event['id']; ?>">View Details</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Modal for image zoom -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div class="caption" id="caption"></div>
</div>

<h3>My Registered Events</h3>
<a href="view_registered_events.php">View Events I've Registered</a>

<h3>Profile Management</h3>
<a href="view_profile.php">View Profile</a> |
<a href="edit_profile.php">Edit Profile</a>

<br><br>
<a href="user_logout.php">Logout</a>

<script>
    // Function to open modal and show full-size image
    function openModal(imageUrl) {
        var modal = document.getElementById("imageModal");
        var modalImage = document.getElementById("modalImage");

        modal.style.display = "block";
        modalImage.src = imageUrl;
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }
</script>

</body>
</html>
