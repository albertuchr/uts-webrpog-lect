<?php
session_start();
require 'db_config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}
if (isset($_GET['cancel_event_id'])) {
    $cancel_event_id = intval($_GET['cancel_event_id']);

    $check_stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = ? AND user_id = ?");
    $check_stmt->execute([$cancel_event_id, $_SESSION['user_id']]);
    $registration = $check_stmt->fetch();

    if ($registration) {
        $delete_stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
        $delete_stmt->execute([$cancel_event_id, $_SESSION['user_id']]);

        header("Location: view_registered_events.php");
        exit;
    } else {
        echo "Error: Registration not found or already canceled.";
        exit;
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620;
            color: #f4ebd0; 
            font-family: 'Poppins', sans-serif;
        }
        h2 {
            font-family: 'Playfair Display', serif;
            color: #f4ebd0;
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
            border-radius: 10px; 
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #d6ad60;
        }
        .table th, .table td {
            border: 1px solid #d6ad60; 
        }
        .table th {
            background-color: #d6ad60; 
            color: #122620;
            font-family: 'Playfair Display', serif;
        }
        .table td {
            background-color: #f4ebd0;
            color: #122620;
        }
        .btn-view, .btn-cancel {
            margin-right: 5px;
            font-family: 'Poppins', sans-serif; 
            font-size: 0.9rem;
        }
        .btn-view {
            background-color: #d6ad60;
            color: #122620;
        }
        .btn-cancel {
            background-color: #f4ebd0;
            color: #122620;
        }
        .btn-view:hover, .btn-cancel:hover {
            opacity: 0.85;
        }
        .footer {
            color: #f4ebd0;
            text-align: center;
            padding: 10px 0;
        }
        a {
            color: #d6ad60;
        }
        a:hover {
            color: #f4ebd0;
        }
        .btn-cancel {
            background-color: #f4ebd0; 
            color: #122620;
            padding-left: 10px;
            padding-right: 10px; 
            margin-left: 10px; 
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
