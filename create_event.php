<?php
session_start();
require 'db_config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $max_participants = $_POST['max_participants'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . basename($image);

    // Move the uploaded image to the server
    if (move_uploaded_file($image_tmp, $image_path)) {
        // Insert event into the database with image path
        $stmt = $pdo->prepare("INSERT INTO events (name, date, time, location, description, max_participants, image) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $date, $time, $location, $description, $max_participants, $image_path]);
        header("Location: update_dashboard.php");
        exit;
    } else {
        $error = "Failed to upload the image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>
<h2>Create New Event</h2>
<?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
<form method="post" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" required><br>
    <label>Date:</label>
    <input type="date" name="date" required><br>
    <label>Time:</label>
    <input type="time" name="time" required><br>
    <label>Location:</label>
    <input type="text" name="location" required><br>
    <label>Description:</label>
    <textarea name="description"></textarea><br>
    <label>Max Participants:</label>
    <input type="number" name="max_participants" required><br>
    <label>Upload Image:</label>
    <input type="file" name="image" required><br>
    <button type="submit">Create Event</button>
</form>
</body>
</html>
