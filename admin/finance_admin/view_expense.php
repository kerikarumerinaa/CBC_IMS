<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    $query = "SELECT * FROM expenses WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $expense_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $expense_data = $result->fetch_assoc();
        $stmt->close();

        if ($expense_data) {
            // Process fetched data
            // Example: echo $expense_data['description'];
        } else {
            header("Location: transactions.php");
            exit;
        }
    } else {
        echo "<script>alert('Error preparing query: {$conn->error}');</script>";
    }
} else {
    header("Location: transactions.php");
    exit;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="addexpenses.css">
</head>
<body>
    <div class="container">
        <?php include '../../includes/sidebar.php'; ?>
        <main>
            <h2>View Expense</h2>
            <?php if ($expense_data): ?>
            <form method="POST" action="">
                <label for="voucher_number">Voucher Number:</label>
                <input type="text" id="voucher_number" name="voucher_number" value="<?php echo $expense_data['voucher_number']; ?>" readonly>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" value="<?php echo $expense_data['amount']; ?>" readonly>

                <label for="check_number">Check Number:</label>
                <input type="text" id="check_number" name="check_number" value="<?php echo $expense_data['check_number']; ?>" readonly>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $expense_data['date']; ?>" readonly>

                <label for="description">Description:</label>
                <input id="description" name="description" value="<?php echo $expense_data['description']; ?>" readonly></input>


                <div class="back-button">
                <a href="transactions.php" class="back-btn">Back to Transactions</a>
                </div>
            </form>
            <?php else: ?>
      <p>No collection data found.</p>
    <?php endif; ?>
        </main>
    </div>
</body>
</html>