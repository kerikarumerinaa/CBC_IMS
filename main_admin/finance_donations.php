<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Donations</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="finance_donations.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <main>
    <div class="transactions-header">
    <h2>Donations</h2>
    </div>

      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Activity</th>
            <th>Date</th> 
          </tr>
        </thead>
        <tbody>