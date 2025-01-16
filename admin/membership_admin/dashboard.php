<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'membership_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Dashboard</title>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Importing Material Symbols for Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Link to dashboard-specific styles -->
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Include the sidebar -->
        <?php include '../../includes/sidebar.php'; ?>
        
        <!-- Include database connection -->
        <?php include '../../includes/db_connection.php'; ?>

        <main>
            <h1>Dashboard</h1>

            <!-- Chart Section for Sex and Network -->
            <div class="chart-row">
            <!-- Chart for Sex Distribution -->
            <div class="chart-container">
                <h3>Number of Members by Gender</h3>
                <canvas id="sexChart" width="500" height="300"></canvas>
            </div>

            <!-- Chart for Network Distribution -->
            <div class="chart-container">
                <h3>Number of Members by Network</h3>
                <canvas id="networkChart" width="500" height="300"></canvas>
            </div>

             <!-- Chart for Member Activity Status -->
            <!-- <div class="chart-container">
                <h3>Member Activity Status</h3>
                <canvas id="memberStatusChart" width="500" height="300"></canvas>
            </div> -->
        </div> 

        <!-- Filter and Worship Attendance Chart -->
        <div class="filter-row">
            <label for="attendanceFilter">Select Attendance Period:</label>
            <select id="attendanceFilter" onchange="updateAttendanceChart()">
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <div class="chart-full-width">
            <h3>Worship Attendance</h3>
            <canvas id="attendanceChart" width="500" height="300"></canvas>
        </div>

            <script>
                // Fetch data from the backend (get_chart_data.php)
                fetch('../../includes/get_chart_data.php')
                    .then(response => response.json())
                    .then(data => {
                        ////////////////// Chart for Gender (Sex Distribution)
                        const sexCtx = document.getElementById('sexChart').getContext('2d');
                        const sexLabels = data.memberSexData.map(item => item.sex); // Sex labels (Male, Female, etc.)
                        const sexCounts = data.memberSexData.map(item => item.count); // Sex counts

                        new Chart(sexCtx, {
                            type: 'bar',
                            data: {
                                labels: sexLabels,
                                datasets: [{
                                    label: 'Members by Sex',
                                    data: sexCounts,
                                    backgroundColor: ['#3498db', '#e74c3c'], // Customize colors as needed
                                    borderColor: ['#2980b9', '#c0392b'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                indexAxis: 'y', // Horizontal bars
                                responsive: true,
                                plugins: {
                                    legend: { position: 'right' },
                                    title: {
                                        display: true,
                                        text: 'Number of Members by Gender'
                                    }
                                },
                                elements: {
                                    bar: {
                                        borderWidth: 2
                                    }
                                }
                            }
                        });



                        ///////////// Chart for Network Distribution
                        const networkCtx = document.getElementById('networkChart').getContext('2d');
                        const networkLabels = data.memberNetworkData.map(item => item.network); // Network labels
                        const networkCounts = data.memberNetworkData.map(item => item.count); // Network counts

                        new Chart(networkCtx, {
                            type: 'pie',
                            data: {
                                labels: networkLabels,
                                datasets: [{
                                    label: 'Members by Network',
                                    data: networkCounts,
                                    backgroundColor: ['#1abc9c', '#f1c40f', '#9b59b6', '#e67e22', '#34495e'], // Customize colors as needed
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'top' },
                                    title: {
                                        display: true,
                                        text: 'Number of Members by Network'
                                    }
                                }
                            }
                        });




                        // Worship Attendance Chart
                        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                        let attendanceLabels, attendanceData, attendanceLabel;

                        // Default to weekly data
                        attendanceLabels = data.memberWeeklyData.map(item => `Week ${item.week}`);
                        attendanceData = data.memberWeeklyData.map(item => item.count);
                        attendanceLabel = 'Weekly Worship Attendance';

                        new Chart(attendanceCtx, {
                            type: 'line',
                            data: {
                                labels: attendanceLabels,
                                datasets: [{
                                    label: attendanceLabel,
                                    data: attendanceData,
                                    borderColor: '#3498db',
                                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'top' },
                                    title: { display: true, text: attendanceLabel }
                                }
                            }
                        });

                        // Update chart when filter changes
                        window.updateAttendanceChart = function() {
                            const filter = document.getElementById('attendanceFilter').value;
                            let attendanceLabels, attendanceData, attendanceLabel;

                            if (filter === 'weekly') {
                                attendanceLabels = data.memberWeeklyData.map(item => `Week ${item.week}`);
                                attendanceData = data.memberWeeklyData.map(item => item.count);
                                attendanceLabel = 'Weekly Worship Attendance';
                            } else {
                                attendanceLabels = data.memberMonthlyData.map(item => `Month ${item.month}`);
                                attendanceData = data.memberMonthlyData.map(item => item.count);
                                attendanceLabel = 'Monthly Worship Attendance';
                            }

                            const updatedChart = new Chart(attendanceCtx, {
                                type: 'line',
                                data: {
                                    labels: attendanceLabels,
                                    datasets: [{
                                        label: attendanceLabel,
                                        data: attendanceData,
                                        borderColor: '#3498db',
                                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                                        fill: true,
                                        tension: 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { position: 'top' },
                                        title: { display: true, text: attendanceLabel }
                                    }
                                }
                            });
                        };
                    })

                    // fetch('../../includes/get_member_status.php')
                    // .then(response => response.json())
                    // .then(data => {
                    //     const memberStatusCtx = document.getElementById('memberStatusChart').getContext('2d');

                    //     new Chart(memberStatusCtx, {
                    //         type: 'doughnut',
                    //         data: {
                    //             labels: ['Active Members', 'Inactive Members'],
                    //             datasets: [{
                    //                 data: [data.active, data.inactive],
                    //                 backgroundColor: ['#2ecc71', '#e74c3c'], // Active: Green, Inactive: Red
                    //                 borderColor: ['#27ae60', '#c0392b'],
                    //                 borderWidth: 1
                    //             }]
                    //         },
                    //         options: {
                    //             responsive: true,
                    //             plugins: {
                    //                 legend: { position: 'top' },
                    //                 title: {
                    //                     display: true,
                    //                     text: 'Member Activity Status'
                    //                 }
                    //             }
                    //         }
                    //     });
                    // })

                    .catch(error => console.error('Error fetching chart data:', error));
                    
            </script>

        </main>
    </div>
</body>
</html>
