<!-- saving the attendace -->
<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $attendees = $_POST['attendees'];

    // Convert attendees array to JSON
    $attendees_json = json_encode($attendees);

    $sql = "INSERT INTO attendance_history (date, description, attendees) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $date, $description, $attendees_json);

    if ($stmt->execute()) {
        echo "Attendance saved successfully!";
    } else {
        echo "Error saving attendance: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} 
?>

<!-- display attendance history -->

<!-- <<?php
$query = "SELECT * FROM attendance_history ORDER BY date DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendees = $row["attendees"]; 
        echo "<tr>";
        echo "<td>" . date("F j, Y", strtotime($row["date"])) . "</td>";
        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
        echo "<td>";
        echo "<button onclick='viewDetails(\"" . $attendees . "\")'>View Attendees</button>"; 
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No attendance history found</td></tr>";
}
?> -->




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance History</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="attendance_history.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <main>
      <div class="Members-history-header">
        <h2>Attendance History</h2>
      </div>

      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = "SELECT * FROM attendance_history ORDER BY date DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["date"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='3'>No attendance history found</td></tr>";
            }
        //   ?>
        </tbody>
      </table>