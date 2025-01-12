<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>




<?php
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = "DELETE FROM transactions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "<script>alert('Transaction deleted successfully!'); window.location.href='transactions.php';</script>";
    } else {
        echo "<script>alert('Error deleting transaction: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collections & Expenses</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="transactions.css">
</head>
<body>
  <div class="container">
    <?php include '../../includes/sidebar.php'; ?>

    <main>
      <div class="transactions-header">
        <h2>Transactions</h2>
        <div class="button-group">
          <a href="addcollection.php" class="btn add-collection-btn">Add Collection</a>
          <a href="addexpenses.php" class="btn add-expense-btn">Add Expense</a>
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          $query = "SELECT * FROM transactions";
          $result = $conn->query($query);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='{$row['id']}' data-date='{$row['date']}' data-description='{$row['description']}' data-type='{$row['type']}' data-amount='{$row['amount']}'>
                <td>{$row['id']}</td>
                <td>{$row['date']}</td>
                <td>{$row['description']}</td>
                <td>{$row['type']}</td>
                <td>{$row['amount']}</td>
                <td>
                  <button class='view-btn' data-id='{$row['id']}'>View</button>
                  <a href='transactions.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this transaction?\")'><button class='delete-btn'>Delete</button></a>
                </td>
            </tr>";
              } 
          } else {
              echo "<tr><td colspan='5'>No transactions found</td></tr>";
          }
          $conn->close();
          ?>
          
        </tbody>
      </table>


  <script>
    const viewModal = document.getElementById('view-modal');
    const closeModal = document.getElementById('close-view-modal');
    const viewBtns = document.querySelectorAll('.view-btn');

    viewBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        viewModal.style.display = 'block';
        const id = e.target.getAttribute('data-id');
        const date = e.target.getAttribute('data-date');
        const description = e.target.getAttribute('data-description');
        const type = e.target.getAttribute('data-type');
        const amount = e.target.getAttribute('data-amount');

        viewModal.querySelector('#date').value = date;
        viewModal.querySelector('#description').value = description;
        viewModal.querySelector('#type').value = type;
        viewModal.querySelector('#amount').value = amount;
      });
    });

    closeModal.addEventListener('click', () => {
      viewModal.style.display = 'none';
    });
  </script>

    
    </form>
  </div>
</div>






<script>
 

  

</script>

</body>
</html>
