<?php
session_start();

// Check if the user is authorized
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$donation_data = null; // Initialize variable for donation data

// Check if the correct query parameter is provided
if (isset($_GET['donation_id'])) {
    $donation_id = intval($_GET['donation_id']); // Sanitize input

    $query = "SELECT * FROM donations WHERE donation_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $donation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $donation_data = $result->fetch_assoc(); // Fetch data as an associative array
        $stmt->close();

        // Redirect if no donation data is found
        if (!$donation_data) {
            echo "<script>alert('Donation record not found.'); window.location.href='donations.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error preparing query: {$conn->error}');</script>";
    }
} else {
    // Redirect if no valid ID is provided
    header("Location: donations.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Donation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="adddonations.css">
</head>
<body>
    <div class="container">
        <?php include '../../includes/sidebar.php'; ?>
        <main>
            <h2>View Donation</h2>
            <?php if ($donation_data): ?>
            <form>
                <label for="number">Number:</label>
                <input type="text" id="number" value="<?php echo htmlspecialchars($donation_data['number']); ?>" readonly>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" value="<?php echo htmlspecialchars($donation_data['amount']); ?>" readonly>

                <label for="date_received">Date Received:</label>
                <input type="text" id="date_received" value="<?php echo htmlspecialchars($donation_data['date_received']); ?>" readonly>

                <label for="time_received">Time Received:</label>
                <input type="time" id="time_received" value="<?php echo htmlspecialchars($donation_data['time_received']); ?>" readonly>

                <label for="createdAt">Date Created:</label>
                <input type="date" id="createdAt" value="<?php echo htmlspecialchars($donation_data['createdAt']); ?>" readonly>

                <div class="back-button">
                    <a href="donations.php" class="back-btn">Back to Donations</a>
                </div>
            </form>
            <?php else: ?>
            <p>No donation data found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
