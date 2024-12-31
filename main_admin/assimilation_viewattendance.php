<?php
include '../includes/db_connection.php';

// Get the attendance ID from the query parameter
$attendanceId = $_GET['id'];

// Fetch the attendance record
$query = "SELECT date, description, attendees FROM assimilation_history WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $attendanceId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $date = $row['date'];
    $description = $row['description'];
    $attendeeNames = explode(', ', $row['attendees']);
} else {
    die("Attendance record not found.");
}

// Fetch attendee details (names and timestamps) from the visitors table
$attendeeDetails = [];
foreach ($attendeeNames as $name) {
    $query = "SELECT full_name, timestamp FROM members WHERE full_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $attendeeDetails[] = $result->fetch_assoc();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Details</title>
  <link rel="stylesheet" href="assimilation_viewattendance.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>

    <main>
    <h1>Attendance for <?php echo $date; ?></h1>
    <p><strong>Description:</strong> <?php echo $description; ?></p>

    <h2>Attendees</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendeeDetails as $attendee): ?>
                <tr>
                    <td><?php echo $attendee['full_name']; ?></td>
                    <td><?php echo $attendee['timestamp']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
      <a href="assimilation_history.php"><button>Back</button></a>
    </main>
  </div>
</body>
</html>