<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;
if (!$event_id) {
    die('Event ID is missing.');
}

// Fetch registrants with additional info for the event
$stmt = $pdo->prepare("SELECT registrations.full_name, registrations.email, registrations.phone_number, registrations.date_of_birth, registrations.address 
                       FROM registrations
                       WHERE registrations.event_id = ?");
$stmt->execute([$event_id]);
$registrants = $stmt->fetchAll();

// Handle CSV export
if (isset($_POST['export_csv'])) {
    $filename = "registrants_event_$event_id.csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Full Name', 'Email', 'Phone Number', 'Date of Birth', 'Address']); // CSV column headers

    foreach ($registrants as $registrant) {
        // Format phone number with a single quote to preserve leading zeros in Excel
        $formatted_phone_number = "'".$registrant['phone_number'];

        fputcsv($output, [
            $registrant['full_name'], 
            $registrant['email'], 
            $formatted_phone_number,  // Phone number with preserved leading zero
            $registrant['date_of_birth'], 
            $registrant['address']
        ]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #122620;
        }
        h2 {
            color: #B68D40;
            text-align: center;
            margin-top: 20px;
            font-family: garamond;
            font-size: 50px;
        }

        table {
           
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #d6ad60; /* Gold background for table headers */
            color: white;
            font-family: futura;
        }
        .table td {
            background-color: #f4ebd0; /* White background for table data */
            color: #122620;
        }
        .btn-export {
            background-color: #B68D40; /* Tan color */
            color: white;
            margin-top: 20px;
        }
        .btn-export:hover {
            opacity: 0.85;
        }
        .table-wrapper {
            padding: 20px;
            margin: 20px auto;
            max-width: 90%;
        }
    </style>
</head>
<body>

<h2>Registrants for Event <?= htmlspecialchars($event_id) ?></h2>

<div class="table-wrapper">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrants as $registrant): ?>
            <tr>
                <td><?= htmlspecialchars($registrant['full_name']); ?></td>
                <td><?= htmlspecialchars($registrant['email']); ?></td>
                <td><?= htmlspecialchars($registrant['phone_number']); ?></td>
                <td><?= htmlspecialchars($registrant['date_of_birth']); ?></td>
                <td><?= htmlspecialchars($registrant['address']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center">
    <form method="post">
        <button type="submit" name="export_csv" class="btn btn-export btn-sm">Export to CSV</button>
    </form>
</div>

<div class="text-center">
    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
