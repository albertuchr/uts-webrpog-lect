<?php
session_start();
require 'db_config.php'; // Database connection

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
    $upload_dir = 'uploads/'; // Directory where the image will be uploaded

    // Ensure the uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create directory if not exists
    }

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];
    $image_size = $_FILES['image']['size'];

    // Generate a unique file name to prevent file overwriting
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif']; // Allowed image formats
    $image_name = uniqid('event_', true) . "." . $image_ext;
    $image_path = $upload_dir . $image_name;

    // Validate image
    if ($image_error === 0) {
        if (in_array(strtolower($image_ext), $allowed_exts)) {
            if ($image_size <= 2 * 1024 * 1024) { // Image size limit (2MB)
                // Move the uploaded image to the server
                if (move_uploaded_file($image_tmp, $image_path)) {
                    // Insert event into the database with the image path
                    $stmt = $pdo->prepare("INSERT INTO events (name, date, time, location, description, max_participants, image) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $date, $time, $location, $description, $max_participants, $image_name]);
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $error = "Failed to upload the image.";
                }
            } else {
                $error = "Image size exceeds 2MB limit.";
            }
        } else {
            $error = "Invalid image file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $error = "There was an error uploading the image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
