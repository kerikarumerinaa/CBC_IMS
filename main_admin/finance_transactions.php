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
          <button class="add-collection-btn">Add Collection</button>
          <button class="add-expense-btn">Add Expense</button>
        </div>
      </div>

      <table class="transaction-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Type</th>
            <th>Total Amount</th>
          </tr>
        </thead>
        <tbody>
          <!-- Table data goes here -->
        </tbody>
      </table>

      <!-- Add Collection Modal -->
            <div class="modal" id="addCollectionModal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add Collection</h2>
                <form id="collection-form">
                  <!-- Basic Inputs -->
                  <label for="collection-amount">Amount</label>
                  <input type="number" id="collection-amount" name="amount" required>

                  <label for="collection-date">Date</label>
                  <input type="date" id="collection-date" name="date" required>

                  <label for="collection-category">Category</label>
                  <select id="collection-category" name="category" required>
                    <option value="tithes">Tithes</option>
                    <option value="offerings">Offerings</option>
                    <option value="donations">Donations</option>
                  </select>

                  <label for="collection-description">Description</label>
                  <textarea id="collection-description" name="description" rows="3"></textarea>

                  <!-- Advanced Inputs (Initially hidden) -->
                  <div id="advanced-fields" class="hidden">
                    <label for="collection-donor">Donor Name</label>
                    <input type="text" id="collection-donor" name="donor">

                    <label for="collection-payment-method">Payment Method</label>
                    <select id="collection-payment-method" name="payment_method">
                      <option value="cash">Cash</option>
                      <option value="check">Check</option>
                      <option value="bank_transfer">Bank Transfer</option>
                    </select>
                  </div>

                  <!-- Button to Toggle Advanced Form -->
                  <button type="button" id="toggle-advanced-form">Show Advanced Form</button>

                  <input type="submit" value="Add Collection">
                </form>
              </div>
            </div>



     <script>
        const modal = document.getElementById('addCollectionModal');
        const btn = document.getElementById('add-collection-btn');
        const span = document.querySelector('.close');
        const toggleButton = document.getElementById('toggle-advanced-form');
        const advancedFields = document.getElementById('advanced-fields');

        // Show the modal when "Add Collection" button is clicked
        btn.addEventListener('click', function() {
          modal.style.display = 'block';
        });

        // Close the modal when "X" (close) button is clicked
        span.addEventListener('click', function() {
          modal.style.display = 'none';
        });

        // Toggle the visibility of the advanced form
        toggleButton.addEventListener('click', function() {
          if (advancedFields.classList.contains('hidden')) {
            advancedFields.classList.remove('hidden');
            toggleButton.textContent = 'Hide Advanced Form';
          } else {
            advancedFields.classList.add('hidden');
            toggleButton.textContent = 'Show Advanced Form';
          }
        });

        
    </script>
  </div>
</body>
</html>
