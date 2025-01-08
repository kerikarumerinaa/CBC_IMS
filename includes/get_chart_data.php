<?php
// Include database connection
include('db_connection.php');

// Initialize data arrays
$response = [];

// Fetch data grouped by sex
///////////////////////////////////////  MEMBERSHIP DASHBAORD   //////////////////////////////////////////

$sexQuery = "SELECT sex, COUNT(*) AS count FROM members GROUP BY sex";
$sexResult = mysqli_query($conn, $sexQuery);
$sexData = [];
while ($row = mysqli_fetch_assoc($sexResult)) {
    $sexData[] = $row;
}
    $response['sexData'] = $sexData;


// Fetch data grouped by network
$networkQuery = "SELECT network, COUNT(*) AS count FROM members GROUP BY network";
$networkResult = mysqli_query($conn, $networkQuery);
$networkData = [];
while ($row = mysqli_fetch_assoc($networkResult)) {
    $networkData[] = $row;
}
    $response['networkData'] = $networkData;

// Fetch weekly attendance data (dummy data for now, update based on actual data)


$weeklyAttendanceQuery = "SELECT WEEK(date) AS week, COUNT(DISTINCT attendees) AS count FROM attendance_history GROUP BY week ORDER BY week";
$weeklyResult = mysqli_query($conn, $weeklyAttendanceQuery);
$weeklyData = [];
while ($row = mysqli_fetch_assoc($weeklyResult)) {
    $weeklyData[] = $row;
}
$response['weeklyData'] = $weeklyData;

$monthlyAttendanceQuery = "SELECT MONTH(date) AS month, SUM(attendees) AS count FROM attendance_history GROUP BY month ORDER BY month";
$monthlyResult = mysqli_query($conn, $monthlyAttendanceQuery);
$monthlyData = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthlyData[] = $row;
}
$response['monthlyData'] = $monthlyData;

///////////////////////////////////////  ASSIMILATION DASHBAORD   //////////////////////////////////////////
// Fetch data grouped by sex
$sexQuery = "SELECT sex, COUNT(*) AS count FROM visitors GROUP BY sex";
$sexResult = mysqli_query($conn, $sexQuery);
$sexData = [];
while ($row = mysqli_fetch_assoc($sexResult)) {
    $sexData[] = $row;
}
$response['sexData'] = $sexData;

// Fetch data grouped by network
$networkQuery = "SELECT network, COUNT(*) AS count FROM visitors GROUP BY network";
$networkResult = mysqli_query($conn, $networkQuery);
$networkData = [];
while ($row = mysqli_fetch_assoc($networkResult)) {
    $networkData[] = $row;
}
$response['networkData'] = $networkData;

// Fetch weekly attendance data (dummy data for now, update based on actual data)

// $weeklyAttendanceQuery = "SELECT WEEK(date) AS week, COUNT(*) AS count FROM assimilation_history GROUP BY week ORDER BY week";
// $weeklyResult = mysqli_query($conn, $weeklyAttendanceQuery);
// $weeklyData = [];
// while ($row = mysqli_fetch_assoc($weeklyResult)) {
//     $weeklyData[] = $row;
// }
// $response['weeklyData'] = $weeklyData;

// $monthlyAttendanceQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM assimilation_history GROUP BY month ORDER BY month";
// $monthlyResult = mysqli_query($conn, $monthlyAttendanceQuery);
// $monthlyData = [];
// while ($row = mysqli_fetch_assoc($monthlyResult)) {
//     $monthlyData[] = $row;
// }
// $response['monthlyData'] = $monthlyData;

///////////////////////////////////////  FINANCE DASHBAORD   //////////////////////////////////////////

// Fetch weekly collections and expenses

// Get the selected time range (default is weekly)
$timeRange = isset($_GET['range']) ? $_GET['range'] : 'weekly';

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cbc_ims');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$data = [];
$labels = [];
$collections = [];
$expenses = [];

// Query based on selected time range
switch ($timeRange) {
    case 'monthly':
        $query = "
            SELECT 
                MONTH(date) AS period, 
                SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
            FROM transactions
            WHERE YEAR(date) = YEAR(CURRENT_DATE)
            GROUP BY period
            ORDER BY period;
        ";
        break;
    case 'yearly':
        $query = "
            SELECT 
                YEAR(date) AS period, 
                SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
            FROM transactions
            GROUP BY period
            ORDER BY period;
        ";
        break;
    case 'weekly':
    default:
        $query = "
            SELECT 
                WEEK(date) AS period, 
                SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
            FROM transactions
            WHERE YEAR(date) = YEAR(CURRENT_DATE)
            GROUP BY period
            ORDER BY period;
        ";
        break;
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $timeRange === 'monthly' ? "Month {$row['period']}" : ($timeRange === 'yearly' ? "Year {$row['period']}" : "Week {$row['period']}");
        $collections[] = $row['total_collections'];
        $expenses[] = $row['total_expenses'];
    }
}

$conn->close();

// Prepare the response
$data = [
    'labels' => $labels,
    'collections' => $collections,
    'expenses' => $expenses,
];








// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

