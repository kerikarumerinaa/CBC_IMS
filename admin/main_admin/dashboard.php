<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'main_admin') {
    header("Location: ../login.php");
    exit;
}

// Include database connection
include '../../includes/db_connection.php';

// Initialize counts
$totalMembers = 0;
$totalVisitors = 0;

try {
    // Query to get the total number of members
    $memberQuery = "SELECT COUNT(*) AS total_members FROM members";
    $memberResult = $conn->query($memberQuery);
    $totalMembers = $memberResult->fetch_assoc()['total_members'];

    // Query to get the total number of visitors
    $visitorQuery = "SELECT COUNT(*) AS total_visitors FROM visitors";
    $visitorResult = $conn->query($visitorQuery);
    $totalVisitors = $visitorResult->fetch_assoc()['total_visitors'];
} catch (Exception $e) {
    // Handle any potential errors
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">

        <!-- Include the sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <main>
            <h1>Dashboard</h1>

            <div class="insights">
                <!-- Total Members -->
                <div class="active-members">
                    <div class="middle">
                        <div class="left">
                            <h1><?php echo $totalMembers; ?></h1>
                            <h3>Total Members</h3>
                        </div>
                    </div>
                </div>


                <div class="inactive-members">
                    <div class="middle">
                        <div class="left">
                            <h1>0</h1> <!-- Placeholder for new members -->
                            <h3>New Members</h3>
                        </div>
                    </div>
                </div>

                <!-- Visitors -->
                <div class="visitors">
                    <div class="middle">
                        <div class="left">
                            <h1><?php echo $totalVisitors; ?></h1>
                            <h3>Total Visitors</h3>
                        </div>
                    </div>
                </div>

                <!-- Monthly Collection -->
                <div class="monthly-collection">
    <h2>Monthly Collection</h2>
    <div class="line-graph">
        <!-- Background grid -->
        <div class="grid">
            <div></div><div></div><div></div><div></div><div></div><div></div>
        </div>

        <!-- Line graph -->
        <div class="line">
            <svg width="100%" height="100%" viewBox="0 0 600 300">
                <polyline points="50,250 150,200 250,180 350,100 450,120 550,80" />
            </svg>
        </div>

        <!-- Points -->
        <div class="points">
            <div class="point" style="top: 215px; left: 50px;" title="January: $500"></div>
            <div class="point" style="top: 180px; left: 150px;" title="February: $800"></div>
            <div class="point" style="top: 120px; left: 250px;" title="March: $950"></div>
            <div class="point" style="top: 120px; left: 350px;" title="April: $1500"></div>
        </div>

        <!-- Y-axis labels -->
        <div class="labels">
            <div class="y-label">25000</div>
            <div class="y-label">20000</div>
            <div class="y-label">15000</div>
            <div class="y-label">10000</div>
            <div class="y-label">0</div>
        </div>

        <!-- X-axis labels -->
        <div class="labels-x">
            <span>January</span>
            <span>February</span>
            <span>March</span>
            <span>April</span>
            <span>May</span>
            <span>June</span>
        </div>
    </div>
</div>


                <!-- Expenses -->
                <div class="expenses">
            <h2>Monthly Expenses</h2>
            <div class="pie-chart"></div>
            <div class="pie-chart-legend">
                <div class="item">
                    <div class="color-box" style="background-color: #f44336;"></div>
                    <span class="label">Rent</span>
                </div>
                <div class="item">
                    <div class="color-box" style="background-color: #2196f3;"></div>
                    <span class="label">Utilities</span>
                </div>
                <div class="item">
                    <div class="color-box" style="background-color: #4caf50;"></div>
                    <span class="label">Salaries</span>
                </div>
                <div class="item">
                    <div class="color-box" style="background-color: #ffeb3b;"></div>
                    <span class="label">Miscellaneous</span>
                </div>
            </div>
        </div>



                 <!-- Events -->

                 <div class="upcoming-events">
    <h2>Upcoming Events</h2>
    <div class="event-list">
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
                    echo "<li class='event-item'>
                        <div class='event-details'>
                            <p><strong>What:</strong> " . htmlspecialchars($row["event_name"]) . "</p>
                            <p><strong>When:</strong> " . htmlspecialchars($eventDate) . "</p>
                            <p><strong>Time:</strong> " . date('g:i A', strtotime($row["start_time"])) . " - " . date('g:i A', strtotime($row["end_time"])) . "</p>
                        </div>
                    </li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No upcoming events at the moment.</p>";
            }
        ?>
    </div>
</div>

            </div>
        </main>
    </div>
</body>
</html>
