<?php
// Database configuration
$host = 'localhost'; // Change this if you're using a different host
$user = 'root';      // Your database username
$pass = '';          // Your database password
$dbname = 'event_registration_system'; // Database name

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    // echo "Database created successfully!<br>"; // Comment out or remove this line

    // Use the new database
    $pdo->exec("USE $dbname");

    // Create the `users` table
    $usersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($usersTable);
    // echo "Users table created successfully!<br>"; // Comment out or remove this line

    // Create the `events` table
    $eventsTable = "CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        date DATE NOT NULL,
        time TIME NOT NULL,
        location VARCHAR(255) NOT NULL,
        description TEXT,
        max_participants INT NOT NULL,
        status ENUM('open', 'closed', 'canceled') DEFAULT 'open',
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($eventsTable);
    // echo "Events table created successfully!<br>"; // Comment out or remove this line

    // Create the `registrations` table
    $registrationsTable = "CREATE TABLE IF NOT EXISTS registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($registrationsTable);
    // echo "Registrations table created successfully!<br>"; // Comment out or remove this line

    // Alter the `registrations` table to add new columns if they don't exist
    $alterRegistrationsTable = "
        ALTER TABLE registrations 
        ADD COLUMN IF NOT EXISTS full_name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS email VARCHAR(255),
        ADD COLUMN IF NOT EXISTS phone_number VARCHAR(20),
        ADD COLUMN IF NOT EXISTS date_of_birth DATE,
        ADD COLUMN IF NOT EXISTS address TEXT
    ";
    $pdo->exec($alterRegistrationsTable);
    // echo "Registrations table altered successfully with new columns!<br>"; // Comment out or remove this line

} catch (PDOException $e) {
    echo "Error creating or modifying the database: " . $e->getMessage();
}
?>
