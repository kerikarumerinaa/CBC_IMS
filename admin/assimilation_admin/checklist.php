<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['visitor_id'])) {
    $visitor_id = $_GET['visitor_id'];
    $query = "SELECT ebs1, ebs2, nbc, church_recognition, baptism FROM checklist WHERE visitor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $visitor_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $visitor_id = $_POST['id'];
    $ebs1 = isset($_POST['ebs1']) ? 1 : 0;
    $ebs2 = isset($_POST['ebs2']) ? 1 : 0;
    $nbc = isset($_POST['nbc']) ? 1 : 0;
    $church_recognition = isset($_POST['church_recognition']) ? 1 : 0;
    $baptism = isset($_POST['baptism']) ? 1 : 0;

    $query = "UPDATE checklist SET ebs1 = ?, ebs2 = ?, nbc = ?, church_recognition = ?, baptism = ? WHERE visitor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiii", $ebs1, $ebs2, $nbc, $church_recognition, $baptism, $visitor_id);
    if ($stmt->execute()) {
        echo "Checklist updated successfully!";
    } else {
        echo "Error updating checklist!";
    }
}
?>
