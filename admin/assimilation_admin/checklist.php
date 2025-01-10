<?php
// Connect to the database
include '../../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $ebs1 = isset($_POST['ebs1']) ? 1 : 0;
    $ebs2 = isset($_POST['ebs2']) ? 1 : 0;
    $nbc = isset($_POST['nbc']) ? 1 : 0;
    $church_recognition = isset($_POST['church_recognition']) ? 1 : 0;
    $baptism = isset($_POST['baptism']) ? 1 : 0;

    $query = "UPDATE visitor_checklist 
              SET ebs1 = ?, ebs2 = ?, nbc = ?, church_recognition = ?, baptism = ? 
              WHERE visitor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiii", $ebs1, $ebs2, $nbc, $church_recognition, $baptism, $id);

    if ($stmt->execute()) {
        echo "Checklist updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
