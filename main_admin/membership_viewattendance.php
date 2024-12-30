<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];

    // Fetch attendance record
    $sql = "SELECT date, description, attendees FROM attendance_history WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendance_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $attendance = $result->fetch_assoc();
        $attendees = explode(', ', $attendance['attendees']); // Convert comma-separated string to array
    } else {
        echo "<script>alert('Attendance record not found.'); window.location.href = 'attendance_history.php';</script>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'attendance_history.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Details</title>
  <link rel="stylesheet" href="attendance_history.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>

    <main>
      <h2>Attendance Details</h2>
      <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($attendance['date'])); ?></p>
      <p><strong>Description:</strong> <?php echo $attendance['description']; ?></p>
      <h3>Attendees:</h3>
      <ul>
        <?php foreach ($attendees as $attendee): ?>
          <li><?php echo htmlspecialchars($attendee); ?></li>
        <?php endforeach; ?>
      </ul>
      <a href="attendance_history.php"><button>Back to Attendance History</button></a>
    </main>
  </div>
</body>
</html>
