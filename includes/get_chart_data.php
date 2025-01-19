<?php
// Include database connection
include('db_connection.php');

// Initialize data arrays
$response = [];

// Fetch data grouped by sex
///////////////////////////////////////  MEMBERSHIP DASHBAORD   //////////////////////////////////////////

// Fetch data grouped by sex for members (Membership side)
$memberSexQuery = "SELECT sex, COUNT(*) AS count FROM members GROUP BY sex";
$memberSexResult = mysqli_query($conn, $memberSexQuery);
$memberSexData = [];
while ($row = mysqli_fetch_assoc($memberSexResult)) {
    $memberSexData[] = $row;
}
$response['memberSexData'] = $memberSexData; // Data for members

// Fetch data grouped by network for members (Membership side)
$memberNetworkQuery = "SELECT network, COUNT(*) AS count FROM members GROUP BY network";
$memberNetworkResult = mysqli_query($conn, $memberNetworkQuery);
$memberNetworkData = [];
while ($row = mysqli_fetch_assoc($memberNetworkResult)) {
    $memberNetworkData[] = $row;
}
$response['memberNetworkData'] = $memberNetworkData; // Data for members

// Fetch weekly attendance data (dummy data for now, update based on actual data)


//$memberWeeklyAttendanceQuery = "SELECT WEEK(date) AS week, COUNT(DISTINCT attendees) AS count FROM attendance_history GROUP BY week ORDER BY week";
$memberWeeklyAttendanceQuery = "SELECT * FROM attendance_history";

$memberWeeklyResult = mysqli_query($conn, $memberWeeklyAttendanceQuery);
$memberWeeklyData = [];
while ($row = mysqli_fetch_assoc($memberWeeklyResult)) {
    $memberWeeklyData[] = $row;
}
$response['memberWeeklyData'] = $memberWeeklyData;

$memberMonthlyAttendanceQuery = "SELECT MONTH(date) AS month, SUM(attendees) AS count FROM attendance_history GROUP BY month ORDER BY month";
$memberMonthlyResult = mysqli_query($conn, $memberMonthlyAttendanceQuery);
$memberMonthlyData = [];
while ($row = mysqli_fetch_assoc($memberMonthlyResult)) {
    $memberMonthlyData[] = $row;
}
$response['memberMonthlyData'] = $memberMonthlyData;

///////////////////////////////////////  ASSIMILATION DASHBAORD   //////////////////////////////////////////
// Fetch data grouped by sex
$visitorSexQuery = "SELECT sex, COUNT(*) AS count FROM visitors GROUP BY sex";
$visitorSexResult = mysqli_query($conn, $visitorSexQuery);
$visitorSexData = [];
while ($row = mysqli_fetch_assoc($visitorSexResult)) {
    $visitorSexData[] = $row;
}
$response['visitorSexData'] = $visitorSexData; // Data for visitors

// Fetch data grouped by network for visitors (Assimilation side)
$visitorNetworkQuery = "SELECT network, COUNT(*) AS count FROM visitors GROUP BY network";
$visitorNetworkResult = mysqli_query($conn, $visitorNetworkQuery);
$visitorNetworkData = [];
while ($row = mysqli_fetch_assoc($visitorNetworkResult)) {
    $visitorNetworkData[] = $row;
}
$response['visitorNetworkData'] = $visitorNetworkData; // Data for visitors

// Fetch weekly attendance data (dummy data for now, update based on actual data)

//ASSIMILATION - WORSHIP ATTENDANCE CHART(WEEKLY)
//visitorWeeklyAttendanceQuery = "SELECT WEEK(date) AS week, COUNT(*) AS count FROM assimilation_history GROUP BY week ORDER BY week";
$visitorWeeklyAttendanceQuery = "SELECT * FROM assimilation_history";

$weeklyResult = mysqli_query($conn, $visitorWeeklyAttendanceQuery);
$visitorWeeklyData = [];
while ($row = mysqli_fetch_assoc($weeklyResult)) {
    $visitorWeeklyData[] = $row;
}
$response['visitorWeeklyData'] = $visitorWeeklyData;

//ASSIMILATION - WORSHIP ATTENDANCE CHART(MONTHLY)
$visitorMonthlyAttendanceQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM assimilation_history GROUP BY month ORDER BY month";
$monthlyResult = mysqli_query($conn, $visitorMonthlyAttendanceQuery);
$visitorMonthlyData = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $visitorMonthlyData[] = $row;
}
$response['visitorMonthlyData'] = $visitorMonthlyData;

///////////////////////////////////////  FINANCE DASHBAORD   //////////////////////////////////////////


// Fetch weekly collections and expenses
// $collectionQuery = "SELECT WEEK(date) AS week, SUM(amount) AS collection FROM collections GROUP BY week ORDER BY week";
// $collectionResult = mysqli_query($conn, $collectionQuery);
// $collectionsData = [];
// while ($row = mysqli_fetch_assoc($collectionResult)) {
//     $collectionsData[] = $row;
// }
// $response['collectionsData'] = $collectionsData;

// $expenseQuery = "SELECT WEEK(date) AS week, SUM(amount) AS expense FROM expenses GROUP BY week ORDER BY week";
// $expenseResult = mysqli_query($conn, $expenseQuery);
// $expensesData = [];
// while ($row = mysqli_fetch_assoc($expenseResult)) {
//     $expensesData[] = $row;
// }
// $response['expensesData'] = $expensesData;
// Fetch weekly collections and expenses

// $timeRange = isset($_GET['range']) ? $_GET['range'] : 'weekly';

// // Connect to the database
// $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }



// // Query based on time range
// switch ($timeRange) {
//     case 'monthly':
//         $query = "
//             SELECT 
//                 DATE_FORMAT(date, '%M') AS period, 
//                 SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
//                 SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
//             FROM transactions
//             WHERE YEAR(date) = YEAR(CURRENT_DATE)
//             GROUP BY MONTH(date)
//             ORDER BY MONTH(date);
//         ";
//         break;

//     case 'yearly':
//         $query = "
//             SELECT 
//                 YEAR(date) AS period, 
//                 SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
//                 SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
//             FROM transactions
//             GROUP BY period
//             ORDER BY period;
//         ";
//         break;

//     case 'weekly':
//     default:
//         $query = "
//             SELECT 
//                 CONCAT('Week ', WEEK(date)) AS period, 
//                 SUM(CASE WHEN type = 'collection' THEN amount ELSE 0 END) AS total_collections,
//                 SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses
//             FROM transactions
//             WHERE YEAR(date) = YEAR(CURRENT_DATE)
//             GROUP BY WEEK(date)
//             ORDER BY WEEK(date);
//         ";
//         break;
// }

// $result = $conn->query($query);

// if ($result && $result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $labels[] = $row['period'];
//         $collections[] = $row['total_collections'];
//         $expenses[] = $row['total_expenses'];
//     }
// } else {
//     echo json_encode(['error' => $conn->error]);
// }

// $conn->close();

// $data = [];
// $labels = [];
// $collections = [];
// $expenses = [];

header('Content-Type: application/json');
echo json_encode($response);


