<?php
session_start();

// Restrict access if not logged in as a member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "cbc_ims");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="profile-page">
        <main class="profile-content">
            <section class="profile-section">
                <h4>Upcoming Events
                    <span style="float:right;"><?php echo date("F j, Y"); ?></span>
                </h4>
                <div class="profile-details">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-info">
                            
                            <?php
                                $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<ul>";
                                    while ($row = $result->fetch_assoc()) {
                                        $eventDate = date('F j, Y', strtotime($row["event_date"]));
                                        $today = date('F j, Y');
                                        $tomorrow = date('F j, Y', strtotime('+1 day'));
                                        if ($eventDate === $today) {
                                            $eventDate = "Today";
                                        } else if ($eventDate === $tomorrow) {
                                            $eventDate = "Tomorrow";
                                        }
                                        echo "<li><strong>What:</strong> " . $row["event_name"] . "<br>
                                        <strong>When:</strong> " . $eventDate . "<br>
                                        <strong>Time:</strong> " . date('g:i A', strtotime($row["start_time"])) . " - " . date('g:i A', strtotime($row["end_time"])) . "</li>";
                                    }
                                    echo "</ul>";
                                }
                            ?>

                                
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function openModal(button) {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            }
        }
    </script>

    
</body>
</html>
