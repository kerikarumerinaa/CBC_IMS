<!-- /includes/sidebar.php -->
<link rel="stylesheet" href="../assets/sidebar.css"> <!-- Adjust path as needed -->

<aside>
    <div class="top">
        <div class="logo">
            <img src="../assets/cbc-logo.png" alt="CBC Logo000"> <!-- Adjust path if needed -->
        </div>
    </div>
    <div class="sidebar">
        <a href="dashboard.php" class="active">
            <span class="material-symbols-outlined">dashboard</span>
            <h2>Dashboard</h2>
        </a>
        <div class="dropdown">
            <a href="javascript:void(0);" class="membership" onclick="toggleDropdown()">
                <span class="material-symbols-outlined">groups</span>
                <h2>Membership</h2>
            </a>
            <div class="dropdown-content">
                <a href="membership_dashboard.php">Dashboard</a>
                <a href="membership_attendance.php">Attendance</a>
                <a href="membership_members.php">Members</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="javascript:void(0);" class="finance" onclick="toggleFinanceDropdown()">
                <span class="material-symbols-outlined">payments</span>
                <h2>Finance</h2>
            </a>
            <div class="dropdown-content">
                <a href="transactions.php">Transactions</a>
                <a href="finance_dashboard.php">Dashboard</a>
            </div>
        </div>
        <a href="events.php">
            <span class="material-symbols-outlined">event</span>
            <h2>Events</h2>
        </a>
        <a href="logout.php">
            <span class="material-symbols-outlined">logout</span>
            <h2>Logout</h2>
        </a>
    </div>
</aside>

<script src="../script.js"></script> <!-- Include your JavaScript file -->
