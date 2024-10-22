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

// Fetch event details for display
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    die('Event not found.');
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];

    // Insert registration with additional information
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
        .navbar {
            background-color: #122620; /* Match background color */
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif; /* Stylish serif font for logo */
            font-size: 2rem;
            font-weight: 700;
            color: #d6ad60 !important; /* Gold color for branding */
        }
        .form-control {
            background-color: #f4ebd0; /* Cream for form inputs */
            color: #122620; /* Dark text for readability */
        }
        .form-label {
            color: #f4ebd0; /* Cream for form labels */
        }
        .btn-custom {
            background-color: #d6ad60; /* Gold for buttons */
            color: #122620; /* Dark text on gold buttons */
        }
        a {
            color: #d6ad60; /* Gold links */
        }
        a:hover {
            color: #f4ebd0; /* Cream on hover */
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>