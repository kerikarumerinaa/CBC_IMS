<?php
session_start();

// Restrict access if not logged in as a member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "cbc_ims");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <link rel="stylesheet" href="donation.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="donate-container">
        <div class="header">
            <img src="../assets/cbc-logo.png" alt="Logo" class="logo">
            <h1>City Bible Church</h1>
        </div>
        <div class="content">
            <div class="qr-section">
                <div class="qr-code">
                    <img src="../assets/qr-donate.jpg" alt="QR Code">
                </div>
                
            </div>
            <div class="info-section">
                <h2>Scan-to-Donate</h2>
                
                <ul class="donations">
                    <li>Name: Emely Carmelina</li>
                    <li>Number: 09123456789</li>
                    
                </ul>
            </div>
        </div>
    </div>
</body>
</html>