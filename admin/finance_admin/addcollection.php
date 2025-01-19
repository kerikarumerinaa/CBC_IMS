<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';

// Check connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $collection_date = $_POST['date'] ?? null;
  $description = $_POST['description'] ?? null;
  $qty_1000 = $_POST['qty1000'] ?? 0;
  $amount_1000 = $_POST['amount1000'] ?? 0.0;
  $qty_500 = $_POST['qty500'] ?? 0;
  $amount_500 = $_POST['amount500'] ?? 0.0;
  $qty_200 = $_POST['qty200'] ?? 0;
  $amount_200 = $_POST['amount200'] ?? 0.0;
  $qty_100 = $_POST['qty100'] ?? 0;
  $amount_100 = $_POST['amount100'] ?? 0.0;
  $qty_20 = $_POST['qty20'] ?? 0;
  $amount_20 = $_POST['amount20'] ?? 0.0;
  $total_cash = $_POST['cashTotal'] ?? 0.0;
  $check_bank = $_POST['bank'] ?? null;
  $check_number = $_POST['check_number'] ?? null;
  $corporate_supporter = $_POST['corporate_supporter'] ?? null;
  $check_amount = $_POST['check_amount'] ?? 0.0;
  $total_checks = $_POST['checkTotal'] ?? 0.0;
  $general_fund = $_POST['generalFund'] ?? 0.0;
  $savings = $_POST['savings'] ?? 0.0;
  $mission_fund = $_POST['missionFund'] ?? 0.0;
  $total_collections = $_POST['totalCollections'] ?? 0.0;
  $counted_by = $_POST['countedBy'] ?? null;
  $received_by = $_POST['receivedBy'] ?? null;
  $createdAt = date('Y-m-d H:i:s'); // Current timestamp

  $type = 'Collection'; // Fixed type for transactions

  // Begin a transaction
  $conn->begin_transaction();

  try {
      // Insert into transactions table
      $query1 = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
      $stmt1 = $conn->prepare($query1);
      if ($stmt1) {
          $stmt1->bind_param('sdss', $description, $total_collections, $collection_date, $type);
          $stmt1->execute();
          $transaction_id = $conn->insert_id; // Get the last inserted ID
          $stmt1->close();
      } else {
          throw new Exception("Error preparing query for transactions: {$conn->error}");
      }

      // Insert into collections table with the same transaction_id
      $query2 = "INSERT INTO collections (
          id, collection_date, description, qty_1000, amount_1000, qty_500, amount_500, qty_200, amount_200, 
          qty_100, amount_100, qty_20, amount_20, total_cash, check_bank, check_number, corporate_supporter, 
          check_amount, total_checks, general_fund, savings, mission_fund, total_collections, counted_by, 
          received_by, createdAt
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt2 = $conn->prepare($query2);
      if ($stmt2) {
          $stmt2->bind_param(
              'issiiiiiiiiidssssdddddssss', 
              $transaction_id, $collection_date, $description, $qty_1000, $amount_1000, $qty_500, $amount_500, $qty_200, 
              $amount_200, $qty_100, $amount_100, $qty_20, $amount_20, $total_cash, $check_bank, $check_number, 
              $corporate_supporter, $check_amount, $total_checks, $general_fund, $savings, $mission_fund, 
              $total_collections, $counted_by, $received_by, $createdAt
          );
          $stmt2->execute();
          $stmt2->close();
      } else {
          throw new Exception("Error preparing query for collections: {$conn->error}");
      }

      // Commit transaction
      $conn->commit();
      echo "<script>alert('Collection added successfully!'); window.location.href='transactions.php';</script>";
  } catch (Exception $e) {
      // Rollback on error
      $conn->rollback();
      echo "<script>alert('Error adding collection: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');</script>";
  }

  // Close the connection
  $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Collection</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="addcollection.css">
</head>
<body>
  <div class="container">
    <?php include '../../includes/sidebar.php'; ?>

    <main>
    <h2>City Bible Church of Muntinlupa</h2>
  <h3>Weekly Collections Summary</h3><br>
  <form method="POST" action="" id="collectionsForm">
    <label for="date">Date:</label>
    <input type="date" id="date" name="date">

    <label for="description">Description:</label>
    <select id="description" name="description">
      <option value="Sunday Service">Sunday Service</option>
      <option value="Mid Week Service">Mid Week Service</option>
      <option value="Other">Other</option>
    </select>


    <h4>Cash Count</h4>
    <table>
      <thead>
        <tr>
          <th>Bills</th>
          <th>Qty</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
    <tr>
        <td>1,000</td>
        <td><input id="qty1000" name="qty1000" type="number" class="qty" data-value="1000" oninput="updateAmount(this)" required></td>
        <td><input id="amount1000" name="amount1000" type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>500</td>
        <td><input id="qty500" name="qty500" type="number" class="qty" data-value="500" oninput="updateAmount(this)" required></td>
        <td><input id="amount500" name="amount500" type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>200</td>
        <td><input id="qty200" name="qty200" type="number" class="qty" data-value="200" oninput="updateAmount(this)" required></td>
        <td><input id="amount200" name="amount200" type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>100</td>
        <td><input id="qty100" name="qty100" type="number" class="qty" data-value="100" oninput="updateAmount(this)" required></td>
        <td><input id="amount100" name="amount100" type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>20</td>
        <td><input id="qty20" name="qty20" type="number" class="qty" data-value="20" oninput="updateAmount(this)" required></td>
        <td><input id="amount20" name="amount20" type="number" class="amount" readonly required></td>
    </tr>
    <tr>
          <td colspan="2"><strong>Total Cash</strong></td>
          <td><input type="number" id="cashTotal" name="cashTotal" readonly></td>
        </tr>
</tbody>
    
    
    <table>
  <thead>
    <h4>Checks</h4>
    <tr>
      <th>Bank</th>
      <th>Check #</th>
      <th>Corporate Supporters & Others</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><input id="bank" name="bank" type="text" placeholder="Bank" required></td>
      <td><input id="check_number" name="check_number" type="text" placeholder="Check #" required></td>
      <td><input id="corporate_supporter" name="corporate_supporter" type="text" placeholder="Corporate Supporter" required></td>
      <td>
        <input 
          id="check_amount"
          name="check_amount"
          type="number" 
          class="Checkamount" 
          oninput="updateCheckTotal()" 
          placeholder="Amount"
        >
      </td>
    </tr>
    <!-- Add more rows as needed -->
    <tr>
      <td colspan="3"><strong>Total Checks</strong></td>
      <td><input type="number" id="checkTotal" name="checkTotal" readonly></td>
    </tr>
  </tbody>
</table>


    <h4>Breakdown of Total Collections</h4>
<table>
  <tbody>
    <tr>
      <td>General Fund</td>
      <td><input type="number" id="generalFund" name="generalFund" readonly></td>
    </tr>
    <tr>
      <td>Savings (20%)</td>
      <td><input type="number" id="savings" name="savings" readonly></td>
    </tr>
    <tr>
      <td>Mission Fund (10%)</td>
      <td><input type="number" id="missionFund" name="missionFund" readonly></td>
    </tr>
    <tr>
      <td><strong>Total</strong></td>
      <td><input type="number" id="totalCollections" name="totalCollections" readonly></td> 
    </tr>
  </tbody>
</table>

    <label for="countedBy">Counted By:</label>
    <input type="text" id="countedBy" name="countedBy" required>
    <label for="receivedBy">Received By:</label>
    <input type="text" id="receivedBy" name="receivedBy" required><br>


    <button type="submit" class="save-btn">Save Collection</button>
    <a href="transactions.php" class="back-btn">Back</a>
  </form>
  


<script>
  function updateAmount(input) {
    const qty = parseInt(input.value) || 0; // Get the quantity, default to 0 if empty
    const denomination = parseInt(input.dataset.value) || 0; // Get the denomination from data-value
    const amountField = input.parentElement.nextElementSibling.querySelector('input.amount'); // Find the corresponding amount field
    amountField.value = qty * denomination; // Calculate and set the amount

    updateCashTotal(); // Update the total cash after changing the amount
  }

  function updateCashTotal() {
    const amountInputs = document.querySelectorAll('.amount'); // Select all amount fields
    let cashTotal = 0;

    amountInputs.forEach(amount => {
      cashTotal += parseInt(amount.value) || 0; // Sum up all amount values
    });

    document.getElementById('cashTotal').value = cashTotal; // Set the total cash value
    updateTotalCollections(); // Update the total collections
  }

  function updateCheckTotal() {
    const checkAmountInputs = document.querySelectorAll('.Checkamount'); // Select all check amount fields
    let checkTotal = 0;

    checkAmountInputs.forEach(check => {
      checkTotal += parseFloat(check.value) || 0; // Sum up all check amount values
    });

    document.getElementById('checkTotal').value = checkTotal.toFixed(2); // Set the total check value
    updateTotalCollections(); // Update the total collections
  }

  function updateTotalCollections() {
    const cashTotal = parseInt(document.getElementById('cashTotal').value) || 0;
    const checkTotal = parseFloat(document.getElementById('checkTotal').value) || 0;
    const totalCollections = cashTotal + checkTotal;

    document.getElementById('totalCollections').value = totalCollections.toFixed(2);
    document.getElementById('savings').value = (totalCollections * 0.2).toFixed(2);
    document.getElementById('missionFund').value = (totalCollections * 0.1).toFixed(2);
    document.getElementById('generalFund').value = (totalCollections).toFixed(2); // Remaining percentage
  }

  // Attach event listeners for real-time updates
  document.querySelectorAll('.qty').forEach(input => {
    input.addEventListener('input', () => updateAmount(input));
  });

  document.querySelectorAll('.Checkamount').forEach(input => {
    input.addEventListener('input', updateCheckTotal);
  });
</script>
</body>
</html>