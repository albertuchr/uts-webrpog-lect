<?php
session_start();
require 'db_config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
if (!$event_id) {
    die('Event ID is missing.');
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die('Event not found.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id, full_name, email, phone_number, date_of_birth, address) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $event_id, $full_name, $email, $phone_number, $date_of_birth, $address]);

    header("Location: view_registered_events.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Additional Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620;
            color: #f4ebd0;
            font-family: 'Poppins', sans-serif;
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
        .form-control {
            background-color: #f4ebd0;
            color: #122620;
        }
        .form-label {
            color: #f4ebd0;
        }
        .btn-custom {
            background-color: #d6ad60;
            color: #122620;
        }
        a {
            color: #d6ad60;
        }
        a:hover {
            color: #f4ebd0;
        }
    </style>
</head>
<body>
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
        <h2>Register for Event: <?= htmlspecialchars($event['name']); ?></h2>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth:</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Home Address:</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-custom">Complete Registration</button>
        </form>

        <br>
        <a href="user_dashboard.php" class="btn btn-outline-dark">Cancel</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>