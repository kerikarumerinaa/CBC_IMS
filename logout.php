<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header('Location: login.php'); // Change 'login.php' to your actual login page
exit();
?>
