<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Collection</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="finance_addcollection.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>

    <?php
        // Include database connection
        include '../includes/db_connection.php'; // Ensure this is at the top of the file

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $date = $_POST['date'];
          $description = $_POST['description'];
          $totalAmount = $_POST['total_amount'];
          $type = 'Collection'; // Automatically set the type to "Collection"
  
           // Insert into the transactions table
          $query = "INSERT INTO transactions (date, description, type, amount) VALUES (?, ?, ?, ?)";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('sssi', $date, $description, $type, $totalAmount);

          if ($stmt->execute()) {
              // Redirect to transactions page after successful save
              header("Location: finance_transactions.php");
              exit();
          } else {
              echo "<script>alert('Error saving collection: {$conn->error}');</script>";
          }
          $stmt->close();
      }
      ?>


    <main>
    <h2>City Bible Church of Muntinlupa</h2>
  <h3>Weekly Collections Summary</h3>
  <form method="POST" action="" id="collectionsForm">
    <label for="date">Date:</label>
    <input type="date" id="date" name="date"><br><br>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description"><br><br>


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
        <td><input type="number" class="qty" data-value="1000" oninput="updateAmount(this)"></td>
        <td><input type="number" class="amount" readonly></td>
    </tr>
    <tr>
        <td>500</td>
        <td><input type="number" class="qty" data-value="500" oninput="updateAmount(this)"></td>
        <td><input type="number" class="amount" readonly></td>
    </tr>
    <tr>
        <td>200</td>
        <td><input type="number" class="qty" data-value="200" oninput="updateAmount(this)"></td>
        <td><input type="number" class="amount" readonly></td>
    </tr>
    <tr>
        <td>100</td>
        <td><input type="number" class="qty" data-value="100" oninput="updateAmount(this)"></td>
        <td><input type="number" class="amount" readonly></td>
    </tr>
    <tr>
        <td>20</td>
        <td><input type="number" class="qty" data-value="20" oninput="updateAmount(this)"></td>
        <td><input type="number" class="amount" readonly></td>
    </tr>
    <tr>
          <td colspan="2"><strong>Total Cash</strong></td>
          <td><input type="number" id="cashTotal" readonly></td>
        </tr>
</tbody>
    </table>

    <h4>Others (Foreign Currency)</h4>
    <table>
      <thead>
        <tr>
          <th>Currency</th>
          <th>Denomination</th>
          <th>FX Rate</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text"></td>
          <td><input type="number"></td>
          <td><input type="number" step="0.01"></td>
          <td><input type="number" readonly></td>
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
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text"></td>
          <td><input type="text"></td>
          <td><input type="number"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Total Checks</strong></td>
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
          <td>Building Fund</td>
          <td><input type="number" id="buildingFund" readonly></td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td><input type="number" id="totalCollections" readonly></td>
        </tr>
      </tbody>
    </table>

    <label for="countedBy">Counted By:</label>
    <input type="text" id="countedBy" name="countedBy"><br><br>
    <label for="receivedBy">Received By:</label>
    <input type="text" id="receivedBy" name="receivedBy"><br><br>



    <button type="button" class="calculate-totals-btn" onclick="calculateTotals()">Calculate Totals</button>
    <button type="submit" class="save-btn">Save Collection</button>

    
  </form>
    <script>
    function updateAmount(input) {
        const qty = parseInt(input.value) || 0; // Get the quantity, default to 0 if empty
        const denomination = parseInt(input.dataset.value) || 0; // Get the denomination from data-value
        const amountField = input.parentElement.nextElementSibling.querySelector('.amount'); // Find the corresponding amount field
        amountField.value = qty * denomination; // Calculate and set the amount

        updateCashTotal(); // Update the total cash after changing the amount
    }

    function updateCashTotal() {
        const amountInputs = document.querySelectorAll('.amount');
        let cashTotal = 0;

        amountInputs.forEach(amount => {
            cashTotal += parseInt(amount.value) || 0;
        });

        document.getElementById('cashTotal').value = cashTotal;
        updateTotalCollections();
    }

    function updateTotalCollections() {
        const cashTotal = parseInt(document.getElementById('cashTotal').value) || 0;
        const checkTotal = parseInt(document.getElementById('checkTotal').value) || 0;
        const totalCollections = cashTotal + checkTotal;

        document.getElementById('totalCollections').value = totalCollections;
        document.getElementById('savings').value = (totalCollections * 0.2).toFixed(2);
        document.getElementById('missionFund').value = (totalCollections * 0.1).toFixed(2);
        document.getElementById('generalFund').value
    function calculateTotals() {
        updateCashTotal(); // This will also update total collections
    }

    function calculateTotals() {
        updateCashTotal(); // This will also update total collections
    }
  }
</script>
</body>
</html>