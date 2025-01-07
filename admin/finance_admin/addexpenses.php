<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>

<?php

include '../../includes/db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $type = 'Expense'; 

    $query = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdss', $description, $amount, $date, $type);

    if ($stmt->execute()) {
        echo "<script>alert('Expense added successfully!'); window.location.href='transactions.php';</script>";
    } else {
        echo "<script>alert('Error adding expense: " . $conn->error . "');</script>";
    }
    $stmt->close();
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
                <textarea id="description" name="description" required></textarea>

                <button type="submit" class="save-btn">Save Expenses</button>
            </form>
        </main>
    </div>
</body>
</html>