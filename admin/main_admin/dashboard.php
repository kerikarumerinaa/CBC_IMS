<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'main_admin') {
    header("Location: ../login.php");
    exit;
}

// Include database connection
include '../../includes/db_connection.php';

// Initialize counts
$totalMembers = 0;
$totalVisitors = 0;

try {
    // Query to get the total number of members
    $memberQuery = "SELECT COUNT(*) AS total_members FROM members";
    $memberResult = $conn->query($memberQuery);
    $totalMembers = $memberResult->fetch_assoc()['total_members'];

    // Query to get the total number of visitors
    $visitorQuery = "SELECT COUNT(*) AS total_visitors FROM visitors";
    $visitorResult = $conn->query($visitorQuery);
    $totalVisitors = $visitorResult->fetch_assoc()['total_visitors'];
} catch (Exception $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">

        <!-- Include the sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <main>
            <h1>Dashboard</h1>

            <div class="insights">
                <!-- Total Members -->
                <div class="active-members">
                    <div class="middle">
                        <div class="left">
                            <h1><?php echo $totalMembers; ?></h1>
                            <h3>Total Members</h3>
                        </div>
                    </div>
                </div>


                <div class="inactive-members">
                    <div class="middle">
                        <div class="left">
                            <h1>0</h1> <!-- Placeholder for new members -->
                            <h3>Inactive Members</h3>
                        </div>
                    </div>
                </div>

                <!-- Visitors -->
                <div class="visitors">
                    <div class="middle">
                        <div class="left">
                            <h1><?php echo $totalVisitors; ?></h1>
                            <h3>Total Visitors</h3>
                        </div>
                    </div>
                </div>

                <!-- Monthly Collection -->
                <div class="monthly-collection">
                    <h2>Monthly Collection</h2>
                </div>

                <!-- Expenses -->
                <div class="expenses">
                    <h2>Expenses</h2>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
