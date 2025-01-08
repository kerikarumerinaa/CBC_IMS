<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'assimilation_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assimilation Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Include the sidebar -->
        <?php include '../../includes/sidebar.php'; ?>
        <?php include '../../includes/db_connection.php'; ?>

        <main>
            <h1>Assimilation Admin Dashboard</h1>

            <!-- Chart Section for Sex and Network -->
            <div class="chart-container">
                <h3>Number of Visitors by Sex</h3>
                <canvas id="sexChart" width="500" height="300"></canvas>
            </div>

            <div class="chart-container">
                <h3>Number of Visitors by Network</h3>
                <canvas id="networkChart" width="500" height="300"></canvas>
            </div>

            <!-- Filter for Attendance -->
            <div>
                <label for="attendanceFilter">Select Attendance Period:</label>
                <select id="attendanceFilter" onchange="updateAttendanceChart()">
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <!-- Chart Section for Worship Attendance -->
            <div class="chart-container">
                <h3>Worship Attendance</h3>
                <canvas id="attendanceChart" width="500" height="300"></canvas>
            </div>

            <script>
                // Fetch data from the backend
                fetch('../../includes/get_chart_data.php')
                    .then(response => response.json())
                    .then(data => {
                        // Sex Chart
                        const sexCtx = document.getElementById('sexChart').getContext('2d');
                        const sexLabels = data.sexData.map(item => item.sex);
                        const sexCounts = data.sexData.map(item => item.count);

                        new Chart(sexCtx, {
                            type: 'bar',
                            data: {
                                labels: sexLabels,
                                datasets: [{
                                    label: 'Members by Gender',
                                    data: sexCounts,
                                    backgroundColor: ['#3498db', '#e74c3c'],
                                }]
                            }
                        });

                        // Network Chart
                        const networkCtx = document.getElementById('networkChart').getContext('2d');
                        const networkLabels = data.networkData.map(item => item.network);
                        const networkCounts = data.networkData.map(item => item.count);

                        new Chart(networkCtx, {
                            type: 'pie',
                            data: {
                                labels: networkLabels,
                                datasets: [{
                                    label: 'Members by Network',
                                    data: networkCounts,
                                    backgroundColor: ['#1abc9c', '#f1c40f', '#9b59b6', '#e67e22'],
                                }]
                            }
                        });

                    });
            </script>

        </main>
    </div>
</body>
</html>