<?php
session_start();
require 'db_config.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $pdo->query("SELECT events.*, COUNT(registrations.id) AS total_registrants
                     FROM events
                     LEFT JOIN registrations ON events.id = registrations.event_id
                     GROUP BY events.id");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; 
            color: #f4ebd0; 
            font-family: 'Garamond', serif;
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px;
            background-color: rgba(18, 38, 32, 0.9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        h2,
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2.5em;
            font-weight: bold;
        }

        h3 {
            font-size: 1.8em;
            font-weight: normal; 
        }

        .btn-gold {
            background-color: #d6ad60; 
            color: #122620; 
            border: none;
            padding: 10px 15px; 
            margin: 5px;
            transition: background-color 0.3s;
        }

        .btn-gold:hover {
            background-color: #b68d40;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            padding: 12px; 
            text-align: left;
            border: 1px solid #f4ebd0; 
            color: white;
            background-color: #b68d40;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #f4ebd0;
            color: #122620;
            background-color: #f4ebd0;
            font-size: 0.9em;
        }

        img {
            max-width: 80px;
            height: auto;
            cursor: pointer;
            border: 2px solid #f4ebd0;
            border-radius: 5px; 
            transition: transform 0.3s;
        }

        img:hover {
            transform: scale(1.1);
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
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            border-radius: 10px;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #f4ebd0;
            font-size: 30px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            img {
                max-width: 50px; 
            }

            h2 {
                font-size: 2em; 
            }

            h3 {
                font-size: 1.5em; 
            }

            .container {
                padding: 15px; 
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, Admin</h2>
            <a href="admin_logout.php" class="btn btn-gold">Logout</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mr-3">Manage Users</h3>
            <a href="manage_users.php" class="btn btn-gold">View All Users</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Available Events</h3>
            <a href="create_event.php" class="btn btn-gold">+New Event</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Max Participants</th>
                    <th>Status</th>
                    <th>Registrants</th>
                    <th>Image</th>
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
                    <td><?= htmlspecialchars($event['max_participants']); ?></td>
                    <td><?= htmlspecialchars($event['status']); ?></td>
                    <td><?= htmlspecialchars($event['total_registrants']); ?></td>
                    <td>
                        <?php if ($event['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($event['image']); ?>" alt="Event Image" onclick="openModal('uploads/<?= htmlspecialchars($event['image']); ?>')">
                        <?php else: ?>
                            <span class="no-image">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-btns">
                        <div class="btn-group" role="group">
                            <a href="manage_event.php?id=<?= $event['id']; ?>&action=edit" class="btn btn-gold btn-sm">Edit</a>
                            <a href="manage_event.php?id=<?= $event['id']; ?>&action=delete" class="btn btn-gold btn-sm" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                            <a href="view_registrants.php?event_id=<?= $event['id']; ?>" class="btn btn-gold btn-sm">View</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImage">
        </div>


    </div>

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
