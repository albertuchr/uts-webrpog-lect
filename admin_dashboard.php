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

        img {
            max-width: 100px;
            height: auto;
            cursor: pointer; 
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
        <th>Image</th> <!-- New column for event image -->
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
            <!-- Display event image if available -->
            <?php if ($event['image']): ?>
                <img src="uploads/<?= htmlspecialchars($event['image']); ?>" alt="Event Image" onclick="openModal('uploads/<?= htmlspecialchars($event['image']); ?>')">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td>
            <a href="manage_event.php?id=<?= $event['id']; ?>&action=edit">Edit</a> |
            <a href="manage_event.php?id=<?= $event['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a> |
            <a href="view_registrants.php?event_id=<?= $event['id']; ?>">View Registrants</a> <!-- Link to view registrants -->
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

<script>
    // Function to open modal and show full-size image
    function openModal(imageUrl) {
        var modal = document.getElementById("imageModal");
        var modalImage = document.getElementById("modalImage");
        var captionText = document.getElementById("");

        modal.style.display = "block";
        modalImage.src = imageUrl;
        captionText.innerHTML = imageUrl; // Optionally, show the image name or some description
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }
</script>

</body>
</html>
