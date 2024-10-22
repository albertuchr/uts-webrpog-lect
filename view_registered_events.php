<?php
session_start();
require 'db_config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT events.*, registrations.full_name, registrations.email, registrations.phone_number, registrations.date_of_birth, registrations.address 
                       FROM registrations
                       JOIN events ON registrations.event_id = events.id
                       WHERE registrations.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$registered_events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registered Events</title>
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
        h2 {
            font-family: 'Playfair Display', serif; /* Stylish serif font for headers */
            color: #f4ebd0; /* Cream for headings */
            text-align: center;
            margin-top: 20px;
            font-size: 2.5rem;
        }

        .table-wrapper {
            padding: 20px;
            margin: 20px auto;
            max-width: 90%;
        }
        .table {
            border-radius: 10px; /* Add border radius to table */
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
            border-collapse: separate;
            border-spacing: 0; /* Ensure border lines look separated */
            border: 1px solid #d6ad60; /* Gold border around the table */
        }
        .table th, .table td {
            border: 1px solid #d6ad60; /* Gold borders for table cells */
        }
        .table th {
            background-color: #d6ad60; /* Gold for table headers */
            color: #122620; /* Dark text for headers */
            font-family: 'Playfair Display', serif; /* Serif font for headers */
        }
        .table td {
            background-color: #f4ebd0; /* Light cream background for table data cells */
            color: #122620; /* Dark text for readability */
        }
        .btn-view, .btn-cancel {
            margin-right: 5px;
            font-family: 'Poppins', sans-serif; /* Matching font */
            font-size: 0.9rem;
        }
        .btn-view {
            background-color: #d6ad60; /* Gold for buttons */
            color: #122620; /* Dark text on gold buttons */
        }
        .btn-cancel {
            background-color: #f4ebd0; /* Cream for cancel button */
            color: #122620; /* Dark text */
        }
        .btn-view:hover, .btn-cancel:hover {
            opacity: 0.85; /* Slight opacity on hover */
        }
        .footer {
            color: #f4ebd0; /* Cream text for footer */
            text-align: center;
            padding: 10px 0;
        }
        a {
            color: #d6ad60; /* Gold links */
        }
        a:hover {
            color: #f4ebd0; /* Cream on hover */
        }
        .btn-cancel {
    background-color: #f4ebd0; /* Cream for cancel button */
    color: #122620; /* Dark text */
    padding-left: 10px;  /* Add padding to the left */
    padding-right: 10px; /* Add padding to the right */
    margin-left: 10px;   /* Add margin on the left to give space between buttons */
}      
    </style>
</head>
<body>

<h2>My Registered Events</h2>

<div class="table-wrapper">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registered_events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['name']); ?></td>
                <td><?= htmlspecialchars($event['date']); ?></td>
                <td><?= htmlspecialchars($event['time']); ?></td>
                <td><?= htmlspecialchars($event['location']); ?></td>
                <td><?= htmlspecialchars($event['full_name']); ?></td>
                <td><?= htmlspecialchars($event['email']); ?></td>
                <td><?= htmlspecialchars($event['phone_number']); ?></td>
                <td><?= htmlspecialchars($event['date_of_birth']); ?></td>
                <td><?= htmlspecialchars($event['address']); ?></td>
                <td>
                    <a href="event_details.php?event_id=<?= $event['id']; ?>" class="btn btn-sm btn-view">View Details</a>
                    <a href="view_registered_events.php?cancel_event_id=<?= $event['id']; ?>" class="btn btn-sm btn-cancel" onclick="return confirm('Are you sure you want to cancel this registration?')">Cancel</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center">
    <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<footer class="footer">
    <div class="container">
        <span>&copy; 2024 My Event Dashboard</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>