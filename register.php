<?php
session_start();
require 'db_config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the password

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email is already registered!";
    } else {
        // Insert user data into database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$name, $email, $password]);
        header("Location: user_login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* Universal Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121212; /* Dark background */
            color: #e0e0e0; /* Light text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for forms */
        .container {
            width: 100%;
            max-width: 400px; /* Maximum width of the form */
            background-color: #1e1e1e; /* Slightly lighter background for forms */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        /* Form styles */
        .form-container {
            display: flex;
            flex-direction: column;
        }

        /* Inputs */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            background-color: #2a2a2a; /* Input field background */
            border: 1px solid #3a3a3a; /* Input border */
            color: #e0e0e0; /* Input text color */
            padding: 10px;
            margin: 10px 0; /* Space between inputs */
            border-radius: 4px; /* Rounded corners */
            font-size: 16px; /* Font size */
        }

        /* Input focus effect */
        input:focus {
            border-color: #6200ea; /* Border color on focus */
            outline: none; /* Remove outline */
        }

        /* Button styles */
        button {
            background-color: #6200ea; /* Button background color */
            color: #ffffff; /* Button text color */
            padding: 10px;
            border: none; /* Remove border */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Pointer effect */
            font-size: 16px; /* Font size */
            margin-top: 10px; /* Space above buttons */
            transition: background-color 0.3s; /* Transition effect */
        }

        /* Button hover effect */
        button:hover {
            background-color: #3700b3; /* Darker shade on hover */
        }

        /* Responsive Styles */
        @media (max-width: 480px) {
            .container {
                width: 90%; /* Full width on small screens */
            }
            input[type="text"],
            input[type="email"],
            input[type="password"],
            button {
                font-size: 14px; /* Decrease font size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form method="post">
                <h2>Register</h2>
                <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="user_login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
