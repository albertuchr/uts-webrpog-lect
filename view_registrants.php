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
<html>
<head>
    <title>View Registrants</title>
</head>
<body>

<h2>Registrants for Event <?= htmlspecialchars($event_id) ?></h2>

<table border="1">
    <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Date of Birth</th>
        <th>Address</th>
    </tr>
    <?php foreach ($registrants as $registrant): ?>
    <tr>
        <td><?= htmlspecialchars($registrant['full_name']); ?></td>
        <td><?= htmlspecialchars($registrant['email']); ?></td>
        <td><?= htmlspecialchars($registrant['phone_number']); ?></td>
        <td><?= htmlspecialchars($registrant['date_of_birth']); ?></td>
        <td><?= htmlspecialchars($registrant['address']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<form method="post">
    <button type="submit" name="export_csv">Export to CSV</button>
</form>

</body>
</html>
