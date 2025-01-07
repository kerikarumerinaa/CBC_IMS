<?php
// Include database connection
include('../../includes/db_connection.php');

// Initialize data arrays
$response = [];

// Fetch data grouped by sex
$sexQuery = "SELECT sex, COUNT(*) AS count FROM members GROUP BY sex";
$sexResult = mysqli_query($conn, $sexQuery);
$sexData = [];
while ($row = mysqli_fetch_assoc($sexResult)) {
    $sexData[] = $row;
}

// Fetch data grouped by network
$networkQuery = "SELECT network, COUNT(*) AS count FROM members GROUP BY network";
$networkResult = mysqli_query($conn, $networkQuery);
$networkData = [];
while ($row = mysqli_fetch_assoc($networkResult)) {
    $networkData[] = $row;
}

// Prepare response
$response['sexData'] = $sexData;
$response['networkData'] = $networkData;

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
