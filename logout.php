<?php
// Start the session
session_start();

// Destroy the session to log out the user
session_unset();   // Remove session variables
session_destroy(); // Destroy the session itself

// Redirect to the login page
header("Location: login.php");
exit();
?>
