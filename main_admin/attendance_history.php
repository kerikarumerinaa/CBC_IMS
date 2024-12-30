<!-- saving the attendace -->
<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include '../includes/db_connection.php';

  $date = $_POST['date'];
  $description = $_POST['description'];
  $attendees = $_POST['attendees']; // Array of attendee names

  // Insert attendance record
  $sql = "INSERT INTO attendance_history (date, description, attendees) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $date, $description, $attendees);

  if ($stmt->execute()) {
      echo '<script>alert("Attendance saved successfully!"); window.location.href = "attendance_history.php";</script>';
  } else {
      echo '<script>alert("Error saving attendance: ' . $stmt->error . '");</script>';
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
          <!-- display attendance history -->
          <?php
            $query = "SELECT id, date, description, attendees FROM attendance_history ORDER BY date DESC";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . date("F j, Y", strtotime($row['date'])) . "</td>
                            <td>{$row['description']}</td>
                            <td>
                                <a href='membership_viewattendance.php?id={$row['id']}'><button>View</button></a>
                                <button onclick='editAttendance({$row['id']})'>Edit</button>
                                <button onclick='deleteAttendance({$row['id']})'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No attendance history found</td></tr>";
            }  
                  ?>
        </tbody>
      </table>

      <!-- View member Modal -->
       <div class="modal" id="view-modal">
         <div class="modal-content">
           <span class="close">&times;</span>
           <h2>Attendees</h2>


           
           
       </div>

      <script>
        

      </script>