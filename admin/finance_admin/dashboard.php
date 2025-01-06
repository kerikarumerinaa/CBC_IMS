<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="dashboard.css"> <!-- Adjust the path to your CSS file -->
</head>
<body>
  <div class="container">

    <!-- Include the sidebar -->
    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/db_connection.php'; ?>

    <!-- MAIN CONTENT -->
    <main>
        <h1>Finance Summary</h1>
        
        <!-- MONTHLY COLLECTIONS -->
        <div class="offering">
            <div class="monthly-offering">
                <h2>Monthly Offering</h2>
                <div class="selection-container">
                    <label for="month">Month:</label>
                    <select id="month">
                        <option value="Jan">January</option>
                        <option value="Feb">February</option>
                        <option value="Mar">March</option>
                        <option value="Apr">April</option>
                        <option value="May">May</option>
                        <option value="Jun">June</option>
                        <option value="Jul">July</option>
                        <option value="Aug">August</option>
                        <option value="Sep">September</option>
                        <option value="Oct">October</option>
                        <option value="Nov">November</option>
                        <option value="Dec">December</option>
                    </select>

                    <label for="year">Year:</label>
                    <input type="number" id="year" min="2023" max="2023">
                </div>
            </div>

            <!-- QUARTERLY COLLECTIONS -->
            <div class="quarterly-offering">
                <h2>Monthly Expenses</h2>
                <div class="middle">
                    <div class="left">
                        <!-- Add content or logic here for quarterly offering -->
                    </div>
                </div>
            </div>
        </div>

    </main>
  </div>

  <script src="../script.js"></script> <!-- Adjust to correct path -->
</body>
</html>
