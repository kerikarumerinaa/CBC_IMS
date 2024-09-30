<?php
$servername = "localhost"; // Typically 'localhost'
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "cbc_ims"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
