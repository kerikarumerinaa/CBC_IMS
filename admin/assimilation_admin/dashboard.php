<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'assimilation_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assimilation Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="container">
    <!-- Include the sidebar -->
    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/db_connection.php'; ?>
 <!-------------------------------------------MAIN--------------------------------------->
<main>
<!-------------------------------------------INSIGHTS------------------------------------->
      <div class="insights">

        <div class="active-members">
          <div class="middle">
            <div class="left">
              <h1>8</h1>
              <h3>Total Visitors</h3>
            </div>
          </div>
        </div>
        <div class="inactive-members">
          <div class="middle">
            <div class="left">
              <h1>20</h1>
              <h3>---</h3>
            </div>
          </div>
        </div>
        <div class="visitors">
          <div class="middle">
            <div class="left">
              <h1>2</h1>
              <h3>New Visitors</h3>
            </div>
          </div>
        </div>
      </div>
<!-------------------------------------------WORSHIP ATTENDANCE------------------------------------->
      <div class="monthly-worship-attendance">
        <h2>Monthly Worship Attendance</h2>
        </div>
        
<!-------------------------------------------VISITOR WORSHIP ATTENDANCE------------------------------------->
        <div class="monthly-visitor-attendance">
            <h2>Monthly Visitor Attendance</h2>
            <div class="middle">
              <div class="left">
              </div>
        </div>
    </main>
  
  </div>
</body>
</html>