<?php
session_start();
require 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email is already registered!";
    } else {
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
        @import url("https://fonts.googleapis.com/css?family=Raleway:400,700");

        *, *:before, *:after {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Raleway", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5dc; 
        }

        .container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .container:hover .top:before, 
        .container:hover .top:after,
        .container:hover .bottom:before, 
        .container:hover .bottom:after,
        .container:active .top:before, 
        .container:active .top:after,
        .container:active .bottom:before, 
        .container:active .bottom:after {
            margin-left: 200px;
            transform-origin: -200px 50%;
            transition-delay: 0s;
        }

        .container:hover .center, 
        .container:active .center {
            opacity: 1;
            transition-delay: 0.2s;
        }

        .top:before, .top:after, 
        .bottom:before, .bottom:after {
            content: "";
            display: block;
            position: absolute;
            width: 200vmax;
            height: 200vmax;
            top: 50%;
            left: 50%;
            margin-top: -100vmax;
            transform-origin: 0 50%;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            z-index: 10;
            opacity: 0.65;
            transition-delay: 0.2s;
        }

        .top:before {
            transform: rotate(45deg);
            background: #d2b48c; 
        }

        .top:after {
            transform: rotate(135deg);
            background: #f5f5dc; 
        }

        .bottom:before {
            transform: rotate(-45deg);
            background: #d4af37; 
        }

        .bottom:after {
            transform: rotate(-135deg);
            background: #333333; 
        }

        .center {
            position: absolute;
            width: 400px;
            height: auto;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 100;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            color: #333; 
        }

        .center input {
            width: 100%;
            padding: 15px;
            margin: 5px;
            border-radius: 1px;
            border: 1px solid #d4af37;
            font-family: inherit;
        }

        .center button {
            padding: 10px;
            margin-top: 10px;
            width: 100%;
            background-color: #d4af37;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .center button:hover {
            background-color: #b58a2d; 
        }

        h2 {
            font-size: 24px;
            margin: 20px 0;
            color: #333333; 
        }

        p {
            margin-top: 10px;
            font-size: 14px;
        }

        p a {
            color: #d4af37; 
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
            <h2>Please Sign In</h2>
            <form method="post">
            <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <h2>&nbsp;</h2>
            <p>Already have an account? <a href="user_login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
