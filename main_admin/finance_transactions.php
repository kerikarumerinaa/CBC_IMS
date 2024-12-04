<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collections & Expenses</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="finance_transactions.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <main>
      <div class="transactions-header">
        <h2>Transactions</h2>
        <div class="button-group">
          <a href="finance_addcollection.php" class="btn add-collection-btn">Add Collection</a>
          <a href="add_expense.html" class="btn add-expense-btn">Add Expense</a>
        </div>
      </div>

      <table class="transaction-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Description</th>
            <th>Type</th>
            <th>Total Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT * FROM transactions ORDER BY date DESC"; // Fetch data from transactions table
          $result = $conn->query($query);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['id']}</td>
                          <td>{$row['date']}</td>
                          <td>{$row['description']}</td>
                          <td>{$row['type']}</td>
                          <td>{$row['amount']}</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='4'>No transactions found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
