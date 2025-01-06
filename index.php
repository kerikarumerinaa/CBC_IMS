<?php
// Start the session
session_start();

// Redirect to the dashboard if already logged in
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'main_admin':
            header("Location: admin/main_admin/dashboard.php");
            break;
        case 'membership_admin':
            header("Location: admin/membership_admin/dashboard.php");
            break;
        case 'finance_admin':
            header("Location: admin/finance_admin/dashboard.php");
            break;
        case 'assimilation_admin':
            header("Location: admin/assimilation_admin/dashboard.php");
            break;
        default:
            session_destroy(); // Invalid role
            header("Location: login.php");
    }
    exit;
}

// If not logged in, redirect to login.php
header("Location: login.php");
exit;
?>
