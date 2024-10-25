<?php
session_start();
require 'db_config.php'; 

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
    $upload_dir = 'uploads/'; 

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); 
    }

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];
    $image_size = $_FILES['image']['size'];

    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
    $image_name = uniqid('event_', true) . "." . $image_ext;
    $image_path = $upload_dir . $image_name;

    if ($image_error === 0) {
        if (in_array(strtolower($image_ext), $allowed_exts)) {
            if ($image_size <= 2 * 1024 * 1024) { 
                if (move_uploaded_file($image_tmp, $image_path)) {
                    
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #122620; 
            color: #b68d40; 
            font-family: 'Garamond', serif; 
        }

        .container {
            margin-top: 30px;
            padding: 30px;
            border-radius: 10px; 
            background-color: rgba(18, 38, 32, 0.9); 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); 
        }

        h2 {
            text-align: center; 
            font-size: 2.5em; 
            font-weight: bold; 
            margin-bottom: 20px; 
        }

        label {
            font-weight: bold; 
        }

        input, textarea {
            background-color: #f4ebd0; 
            color: #122620; 
            border: 1px solid #d6ad60; 
            border-radius: 5px; 
            padding: 10px; 
            width: 100%; 
            margin-bottom: 15px; 
            font-size: 1em; 
        }

        button {
            background-color: #d6ad60; 
            color: #122620; 
            border: none;
            padding: 10px 15px; 
            margin-top: 10px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }

        button:hover {
            background-color: #b68d40; 
            color: white; 
        }

        .error {
            color: red; 
            text-align: center; 
            margin-bottom: 15px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Event</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Date:</label>
            <input type="date" name="date" required>
            <label>Time:</label>
            <input type="time" name="time" required>
            <label>Location:</label>
            <input type="text" name="location" required>
            <label>Description:</label>
            <textarea name="description"></textarea>
            <label>Max Participants:</label>
            <input type="number" name="max_participants" required>
            <label>Upload Image:</label>
            <input type="file" name="image" required>
            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>