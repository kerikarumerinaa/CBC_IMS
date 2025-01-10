<?php
include('db_connection.php');

$response = ['active' => 0, 'inactive' => 0];

// Query all members
$allMembersQuery = "SELECT id FROM members";
$result = $conn->query($allMembersQuery);

if ($result && $result->num_rows > 0) {
    while ($member = $result->fetch_assoc()) {
        $memberId = $member['id'];

        // Check if the member attended within the last 3 months
        $attendanceQuery = "
            SELECT COUNT(*) as attendance_count
            FROM attendance
            WHERE member_id = ? AND attendance_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        
        $stmt = $conn->prepare($attendanceQuery);
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $attendanceResult = $stmt->get_result()->fetch_assoc();
        
        if ($attendanceResult['attendance_count'] > 0) {
            $response['active'] += 1;
        } else {
            $response['inactive'] += 1;
        }
    }
}

// Return the response as JSON
echo json_encode($response);
?>
