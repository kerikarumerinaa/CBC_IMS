<?php
session_start();

// Check if the user is authorized
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

// Handle the deletion process
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // Sanitize the ID
    $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "DELETE FROM donations WHERE donation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>alert('Donation deleted successfully!'); window.location.href='donations.php';</script>";
    } else {
        echo "<script>alert('Error deleting transaction.');</script>";
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
    <title>Online Donations</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="donations.css">
</head>
<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <main>
            <div class="transactions-header">
                <h2>Donations</h2>
                <div class="button-group">
                    <a href="adddonations.php" class="add-donation-btn">Add Donation</a>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Donation ID</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Date Received</th>
                        <th>Time Received</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch donations from the database
                    $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $query = "SELECT * FROM donations";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['donation_id']}</td>
                                <td>{$row['number']}</td>
                                <td>{$row['amount']}</td>
                                <td>{$row['date_received']}</td>
                                <td>{$row['time_received']}</td>
                                <td>{$row['createdAt']}</td>
                                <td>
                                    <a href='view_donation.php?donation_id={$row['donation_id']}' class='view-btn' style='cursor: pointer;'>View</a>
                                    <a href='donations.php?delete_id={$row['donation_id']}' onclick='return confirm(\"Are you sure you want to delete this transaction?\")' class='delete-btn' style='cursor: pointer;'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No donations found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
ss