<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $voucherNumber = $_POST['voucher_number'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $checkNumber = $_POST['check_number'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';
    $createdAt = date('Y-m-d H:i:s');
    $type = 'Expense';

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into transactions table
        $query1 = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
        $stmt1 = $conn->prepare($query1);
        if ($stmt1) {
            // Corrected the bindings
            $stmt1->bind_param('sdss', $description, $amount, $date, $type);
            $stmt1->execute();
            $transaction_id = $conn->insert_id; // Get the last inserted ID
            $stmt1->close();
        } else {
            throw new Exception("Error preparing query for transactions: {$conn->error}");
        }

        // Insert into expenses table
        $query2 = "INSERT INTO expenses (id, voucher_number, amount, check_number, date, description, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        if ($stmt2) {
            // Correct binding for parameters
            $stmt2->bind_param('iisdsss', $transaction_id, $voucherNumber, $amount, $checkNumber, $date, $description, $createdAt);
            $stmt2->execute();
            $stmt2->close();
        } else {
            throw new Exception("Error preparing query for expenses: {$conn->error}");
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Expense and collection added successfully!'); window.location.href='transactions.php';</script>";
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "<script>alert('Error adding data: {$e->getMessage()}');</script>";
    }

    // Close connection
    $conn->close();
}
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
            <h2>Add Expense</h2>
            <form method="POST" action="">
                <label for="voucher_number">Voucher Number:</label>
                <input type="text" id="voucher_number" name="voucher_number" required>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" required>

                <label for="check_number">Check Number:</label>
                <input type="text" id="check_number" name="check_number" required>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="description">Description:</label>
                <select id="description" name="description" required>
                    <option value="">Select a description</option>
                    <option value="Electricity">Electricity</option>
                    <option value="Water bill">Water bill</option>
                    <option value="Rent">Rent</option>
                    <option value="Internet">Internet</option>
                </select>

                <button type="submit" class="save-btn">Save Expenses</button>
                <a href="transactions.php" class="back-btn">Back</a>
            </form>
        </main>
    </div>
</body>
</html>
