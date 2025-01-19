<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $number = $_POST['number'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $date_received = $_POST['date_received'] ?? '';
    $time_received = $_POST['time_received'] ?? '';;
    $createdAt = date('Y-m-d H:i:s');

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into donations table
        $query = "INSERT INTO donations (number, amount, date_received, time_received, createdAt) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            // Correct binding for parameters
            $stmt->bind_param('idsss', $number, $amount, $date_received, $time_received, $createdAt);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing query for donations: {$conn->error}");
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Donation added successfully!'); window.location.href='donations.php';</script>";
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
    <title>Add Donation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="addexpenses.css">
</head>
<body>
    <div class="container">
        <?php include '../../includes/sidebar.php'; ?>

        <main>
            <h2>Add Donation</h2>
            <form method="POST" action="">
                <label for="number">Number:</label>
                <input type="text" id="number" name="number" required>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" required>

                <label for="date_received">Date Received:</label>
                <input type="date" id="date_received" name="date_received" required>

                <label for="time_received">Time Received:</label>
                <input type="time" id="time_received" name="time_received" required>


                <button type="submit" class="save-btn">Save Donation</button>
            </form>
            <a href="donations.php"><button class="back-btn">Back</button></a>
        </main>
    </div>
</body>
</html>
