<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'membership_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>


<!-- saving the attendace -->
<?php
include '../../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $description = $_POST['description'];
    $attendees = $_POST['attendees']; // Comma-separated list of attendee IDs

    // Fetch attendee names from the database
    $attendeeNames = array();
    $attendeeIds = explode(',', $attendees);
    foreach ($attendeeIds as $attendeeId) {
        $query = "SELECT full_name FROM members WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $attendeeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $attendeeNames[] = $row['full_name'];
        }
        $stmt->close();
    }

    // Insert attendance data into the database
    $query = "INSERT INTO attendance_history (date, description, attendees) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $date, $description, implode(', ', $attendeeNames));
    if ($stmt->execute()) {
        header("Location: history.php?status=success");
        exit;
    } else {
        header("Location: history.php?status=error");
        exit;
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
    // Prepare and execute deletion query
    $delete_query = "DELETE FROM attendance_history WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Attendance history deleted successfully'); window.location.href='history.php';</script>";
    } else {
        echo "<script>alert('Error deleting attencdance');</script>";
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
  <link rel="stylesheet" href="history.css">
</head>
<body>
  <div class="container">
    <?php include '../../includes/sidebar.php'; ?>

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
                                <a href='viewattendance.php?id={$row['id']}'><button>View</button></a>
                                <button onclick='editAttendance({$row['id']})'>Edit</button>
                                <a href='history.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this attendance?\")'><button class='delete-btn'>Delete</button></a>

                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No attendance history found</td></tr>";
            }  
                  ?>
        </tbody>
      </table>
    </main> 
  </div>

  <!-- Success Modal -->
  <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3>Attendance Added Successfully!</h3>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Close modal functionality
        function closeModal() {
            document.getElementById('success-modal').style.display = 'none';
        }

        // Automatically show modal if it exists
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.style.display = 'block';
            }
        });
        
    </script>
</body>
</html>
