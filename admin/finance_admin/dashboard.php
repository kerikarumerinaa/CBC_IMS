<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'finance_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="dashboard.css"> <!-- Adjust the path to your CSS file -->
</head>
<body>
  <div class="container">

    <!-- Include the sidebar -->
    <?php include '../../includes/sidebar.php'; ?>
    <?php include '../../includes/db_connection.php'; ?>

    <!-- MAIN CONTENT -->
    <main>
            <h1>Finance Dashboard</h1>
            
            <!-- Dropdown filter for weekly, monthly, and yearly data -->
            <div>
                <label for="timeRange">Select Time Range:</label>
                <select id="timeRange" onchange="updateChart()">
                    <option value="weekly" selected>Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            
            <!-- Chart for collections and expenses -->
            <div class="chart-container">
                <h3>Collection and Expense Chart</h3>
                <canvas id="financeChart" width="500" height="300"</canvas>
            </div>
        </main>
    </div>

    <script>
     let financeChart;

// Fetch chart data and render chart
    fetch(`../../includes/get_chart_data.php`)
        .then(response => response.json())
        .then(data => {
            
            const ctx = document.getElementById('financeChart').getContext('2d');
            financeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Collections',
                            data: data.collections,
                            backgroundColor: '#2ecc71', // Green
                            borderColor: '#27ae60',
                            borderWidth: 1
                        },
                        {
                            label: 'Expenses',
                            data: data.expenses,
                            backgroundColor: '#e74c3c', // Red
                            borderColor: '#c0392b',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Collections and Expenses'
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));

// Update chart based on dropdown selection
function updateChart() {
    const timeRange = document.getElementById('timeRange').value;
    fetchChartData(timeRange);
}

// Initialize chart with default range
fetchChartData('weekly');
    </script>
</body>
</html>