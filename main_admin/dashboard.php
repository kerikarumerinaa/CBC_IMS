<?php
session_start();

// Check if the main admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
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
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <h1>Dashboard</h1>

            <div class="insights">
                <!-- Active Members -->
                <div class="active-members">
                    <div class="middle">
                        <div class="left">
                            <h1>70</h1>
                            <h3>Active members</h3>
                        </div>
                    </div>
                </div>

                <!-- Inactive Members -->
                <div class="inactive-members">
                    <div class="middle">
                        <div class="left">
                            <h1>20</h1>
                            <h3>New members</h3>
                        </div>
                    </div>
                </div>

                <!-- Visitors -->
                <div class="visitors">
                    <div class="middle">
                        <div class="left">
                            <h1>2</h1>
                            <h3>Visitors</h3>
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
