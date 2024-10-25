<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$event_id = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$event_id || !$action) {
    die('Event ID or action is missing.');
}

if ($action == 'delete') {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    header("Location: admin_dashboard.php");
    exit;
}

if ($action == 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        die('Event not found.');
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $max_participants = $_POST['max_participants'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $date, $time, $location, $description, $max_participants, $status, $event_id]);

        header("Location: admin_dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event</title>
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

        input, textarea, select {
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
        <?php if ($action == 'edit'): ?>
            <h2>Edit Event: <?= htmlspecialchars($event['name']); ?></h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($event['name']); ?>" required>
                <label>Date:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($event['date']); ?>" required>
                <label>Time:</label>
                <input type="time" name="time" value="<?= htmlspecialchars($event['time']); ?>" required>
                <label>Location:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required>
                <label>Description:</label>
                <textarea name="description"><?= htmlspecialchars($event['description']); ?></textarea>
                <label>Max Participants:</label>
                <input type="number" name="max_participants" value="<?= htmlspecialchars($event['max_participants']); ?>" required>
                <label>Status:</label>
                <select name="status">
                    <option value="open" <?= $event['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                    <option value="closed" <?= $event['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                    <option value="canceled" <?= $event['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit">Update Event</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
