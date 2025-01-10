<?php
// Include your database connection
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $date = $_POST['date'];
    $description = $_POST['description'];
    $cashTotal = $_POST['cashTotal'];  // Total cash
    $checkTotal = $_POST['checkTotal'];  // Total checks
    $generalFund = $_POST['generalFund'];  // General fund
    $savings = $_POST['savings'];  // Savings (20%)
    $missionFund = $_POST['missionFund'];  // Mission fund (10%)
    $totalCollections = $_POST['totalCollections'];  // Total collections
    $countedBy = $_POST['countedBy'];
    $receivedBy = $_POST['receivedBy'];

    // Insert into collections table
    $sql_collection = "INSERT INTO collections (date, description, cash_total, check_total, general_fund, savings, mission_fund, total_collections, counted_by, received_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_collection = $conn->prepare($sql_collection);
    $stmt_collection->bind_param("ssdddddss", $date, $description, $cashTotal, $checkTotal, $generalFund, $savings, $missionFund, $totalCollections, $countedBy, $receivedBy);

    // Insert into transactions table (example for cash transactions)
    $sql_transaction = "INSERT INTO transactions (description, amount, date, type) VALUES (?, ?, ?, ?)";
    $stmt_transaction = $conn->prepare($sql_transaction);
    $stmt_transaction->bind_param("sdss", $description, $totalCollections, $date, 'Collection');  // Assuming 'Collection' as transaction type

    // Begin transaction to ensure both inserts happen together
    $conn->begin_transaction();

    try {
        // Execute both queries
        $stmt_collection->execute();
        $stmt_transaction->execute();

        // Commit the transaction
        $conn->commit();
        echo "Data has been saved to both tables.";
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "Failed to save data: " . $e->getMessage();
    }
}
?>
