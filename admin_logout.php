<?php
session_start();

// Destroy the session to log out the admin
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page after logout
header("Location: admin_login.php");
exit;
?>
