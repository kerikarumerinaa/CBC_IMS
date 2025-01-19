<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $collection_id = $_GET['id'] ?? null;
    if ($collection_id) {
        $query = "SELECT * FROM collections WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $collection_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $collection_data = $result->fetch_assoc();
            $stmt->close();

            if ($collection_data) {
                // Process fetched data
                // Example: echo $collection_data['description'];
            } else {
                echo "<script>alert('No collection found with the specified ID.'); window.location.href='transactions.php';</script>";
            }
        } else {
            echo "<script>alert('Error preparing query: {$conn->error}');</script>";
        }
    } else {
        echo "<script>alert('Invalid collection ID.'); window.location.href='transactions.php';</script>";
    }
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Collection</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="addcollection.css">
</head>
<body>
  <div class="container">
    <?php include '../../includes/sidebar.php'; ?>

    <main>
    <h2>City Bible Church of Muntinlupa</h2>
    <h3>Collection Details</h3><br>
    <?php if ($collection_data): ?>
      <form id="collectionsForm">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($collection_data['collection_date']) ?>" readonly>

        <label for="description">Description:</label>
        <input id="description" type="text" value="<?= htmlspecialchars($collection_data['description']) ?>" readonly></td>


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
              <td><input id="qty1000" type="number" value="<?= htmlspecialchars($collection_data['qty_1000']) ?>" readonly></td>
              <td><input id="amount1000" type="number" value="<?= htmlspecialchars($collection_data['amount_1000']) ?>" readonly></td>
            </tr>
            <tr>
              <td>500</td>
              <td><input id="qty500" type="number" value="<?= htmlspecialchars($collection_data['qty_500']) ?>" readonly></td>
              <td><input id="amount500" type="number" value="<?= htmlspecialchars($collection_data['amount_500']) ?>" readonly></td>
            </tr>
            <tr>
              <td>200</td>
              <td><input id="qty200" type="number" value="<?= htmlspecialchars($collection_data['qty_200']) ?>" readonly></td>
              <td><input id="amount200" type="number" value="<?= htmlspecialchars($collection_data['amount_200']) ?>" readonly></td>
            </tr>
            <tr>
              <td>100</td>
              <td><input id="qty100" type="number" value="<?= htmlspecialchars($collection_data['qty_100']) ?>" readonly></td>
              <td><input id="amount100" type="number" value="<?= htmlspecialchars($collection_data['amount_100']) ?>" readonly></td>
            </tr>
            <tr>
              <td>20</td>
              <td><input id="qty20" type="number" value="<?= htmlspecialchars($collection_data['qty_20']) ?>" readonly></td>
              <td><input id="amount20" type="number" value="<?= htmlspecialchars($collection_data['amount_20']) ?>" readonly></td>
            </tr>
            <tr>
              <td colspan="2"><strong>Total Cash</strong></td>
              <td><input type="number" id="cashTotal" value="<?= htmlspecialchars($collection_data['total_cash']) ?>" readonly></td>
            </tr>
          </tbody>
        </table>

        <h4>Checks</h4>
        <table>
          <thead>
            <tr>
              <th>Bank</th>
              <th>Check #</th>
              <th>Corporate Supporters & Others</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input id="bank" type="text" value="<?= htmlspecialchars($collection_data['check_bank']) ?>" readonly></td>
              <td><input id="check_number" type="text" value="<?= htmlspecialchars($collection_data['check_number']) ?>" readonly></td>
              <td><input id="corporate_supporter" type="text" value="<?= htmlspecialchars($collection_data['corporate_supporter']) ?>" readonly></td>
              <td><input id="check_amount" type="number" value="<?= htmlspecialchars($collection_data['check_amount']) ?>" readonly></td>
            </tr>
            <tr>
              <td colspan="3"><strong>Total Checks</strong></td>
              <td><input type="number" id="checkTotal" value="<?= htmlspecialchars($collection_data['total_checks']) ?>" readonly></td>
            </tr>
          </tbody>
        </table>

        <h4>Breakdown of Total Collections</h4>
        <table>
          <tbody>
            <tr>
              <td>General Fund</td>
              <td><input type="number" id="generalFund" value="<?= htmlspecialchars($collection_data['general_fund']) ?>" readonly></td>
            </tr>
            <tr>
              <td>Savings (20%)</td>
              <td><input type="number" id="savings" value="<?= htmlspecialchars($collection_data['savings']) ?>" readonly></td>
            </tr>
            <tr>
              <td>Mission Fund (10%)</td>
              <td><input type="number" id="missionFund" value="<?= htmlspecialchars($collection_data['mission_fund']) ?>" readonly></td>
            </tr>
            <tr>
              <td><strong>Total</strong></td>
              <td><input type="number" id="totalCollections" value="<?= htmlspecialchars($collection_data['total_collections']) ?>" readonly></td>
            </tr>
          </tbody>
        </table>

        <label for="countedBy">Counted By:</label>
        <input type="text" id="countedBy" value="<?= htmlspecialchars($collection_data['counted_by']) ?>" readonly>
        <label for="receivedBy">Received By:</label>
        <input type="text" id="receivedBy" value="<?= htmlspecialchars($collection_data['received_by']) ?>" readonly><br>

        <div class="back-button">
        <a href="transactions.php" class="back-btn">Back to Transactions</a>
        <button class="print-btn" onclick="printSection('collectionsForm')">Print Report</button>
        </div>
      </form>
    <?php else: ?>
      <p>No collection data found.</p>
    <?php endif; ?>


  </main>
  </div>
</body>
</html>


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


 
  // Function to print a specific section of the page
  function printSection(sectionId) {
    const sectionToPrint = document.getElementById(sectionId).innerHTML; // Get the content of the section
    const originalContent = document.body.innerHTML; // Save the original content of the page

    document.body.innerHTML = `
      <html>
        <head>
          <title>Print Report</title>
          <link rel="stylesheet" href="addcollection.css">
        </head>
        <body>
          ${sectionToPrint}
        </body>
      </html>
    `; // Replace body with section content

    window.print(); // Trigger print

    document.body.innerHTML = originalContent; // Restore the original content after printing
    window.location.reload(); // Reload the page to restore JavaScript functionality
  }




  
</script>
</body>
</html>
