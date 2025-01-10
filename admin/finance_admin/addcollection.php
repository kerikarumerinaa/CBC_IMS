<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>

<?php

include '../../includes/db_connection.php'; // Include database connection

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $description = $_POST['description'];  // assuming the description is also passed
//   $totalamount = $_POST['totalCollections'];       // total amount calculated from cash + checks
//   $date = $_POST['date'];                // make sure the date is passed correctly
//   $type = 'Collection';                  // Set the type as Collection


//   $query = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
//   $stmt = $conn->prepare($query);
//   $stmt->bind_param('sdss', $description, $totalamount, $date, $type);  // 'd' for double (amount), 's' for string (description, date, type)

//   if ($stmt) {
//     $stmt->bind_param('sdss', $description, $totalamount, $date, $type);
//     if ($stmt->execute()) {
//         echo "<script>alert('Collection added successfully!'); window.location.href='transactions.php';</script>";
//     } else {
//         echo "<script>alert('Error adding collection: {$conn->error}');</script>";
//     }
//     $stmt->close();
// } else {
//     echo "<script>alert('Database error: {$conn->error}');</script>";
// }
// }
// $conn->close();

// 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $description = $_POST['description'];  // Ensure 'description' field is in the form
  $totalAmount = $_POST['totalCollections'];  // Ensure 'totalCollections' field is correctly named in the form
  $date = $_POST['date'];  // Ensure 'date' field is in the form
  $type = 'Collection';  // Set default type

  // Insert into database
  $query = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);

  if ($stmt) {
      $stmt->bind_param('sdss', $description, $totalAmount, $date, $type);  // Bind parameters
      if ($stmt->execute()) {
          echo "<script>alert('Collection added successfully!'); window.location.href='transactions.php';</script>";
      } else {
          echo "<script>alert('Error adding collection: {$conn->error}');</script>";
      }
      $stmt->close();
  } else {
      echo "<script>alert('Database error: {$conn->error}');</script>";
  }
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
        <td><input type="number" class="qty" data-value="1000" oninput="updateAmount(this)" required></td>
        <td><input type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>500</td>
        <td><input type="number" class="qty" data-value="500" oninput="updateAmount(this)" required></td>
        <td><input type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>200</td>
        <td><input type="number" class="qty" data-value="200" oninput="updateAmount(this)" required></td>
        <td><input type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>100</td>
        <td><input type="number" class="qty" data-value="100" oninput="updateAmount(this)" required></td>
        <td><input type="number" class="amount" readonly required></td>
    </tr>
    <tr>
        <td>20</td>
        <td><input type="number" class="qty" data-value="20" oninput="updateAmount(this)" required></td>
        <td><input type="number" class="amount" readonly required></td>
    </tr>
    <tr>
          <td colspan="2"><strong>Total Cash</strong></td>
          <td><input type="number" id="cashTotal" readonly></td>
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
      <td><input type="text" placeholder="Bank" required></td>
      <td><input type="text" placeholder="Check #" required></td>
      <td><input type="text" placeholder="Corporate Supporter" required></td>
      <td>
        <input 
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
      <td><input type="number" id="checkTotal" readonly></td>
    </tr>
  </tbody>
</table>


    <h4>Breakdown of Total Collections</h4>
<table>
  <tbody>
    <tr>
      <td>General Fund</td>
      <td><input type="number" id="generalFund" readonly></td>
    </tr>
    <tr>
      <td>Savings (20%)</td>
      <td><input type="number" id="savings" readonly></td>
    </tr>
    <tr>
      <td>Mission Fund (10%)</td>
      <td><input type="number" id="missionFund" readonly></td>
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
    document.getElementById('generalFund').value = (totalCollections * 0.7).toFixed(2); // Remaining percentage
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