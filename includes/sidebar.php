
<!-- /includes/sidebar.php -->
<link rel="stylesheet" href="../../assets/sidebar.css"> <!-- Adjusted path -->

<aside>
    <div class="top">
        <div class="logo">
            <img src="../../assets/cbc-logo.png" alt="CBC Logo"> <!-- Adjusted path -->
        </div>
    </div>
    <div class="sidebar <?php echo isset($customClass) && $customClass === 'custom-height' ? 'custom-height' : ''; ?>">

        <!-- Main Admin: Shows Dashboard and dropdown sections -->
        <?php if ($_SESSION['role'] === 'main_admin') { ?>
            <a href="../main_admin/dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h2>Dashboard</h2>
            </a>

            <!-- Membership Dropdown -->
            <div class="dropdown">
                <a class="membership" onclick="showMembershipDropDown()">
                    <span class="material-symbols-outlined">groups</span>
                    <h2>Membership</h2>
                </a>
                <div class="dropdown-content membershipDropdown">
                    <a href="../membership_admin/dashboard.php">Dashboard</a>
                    <a href="../membership_admin/attendance.php">Attendance</a>
                    <a href="../membership_admin/history.php">Attendance History</a>
                    <a href="../membership_admin/members.php">Members</a>
                </div>
            </div>

            <!-- Assimilation Dropdown -->
            <div class="dropdown">
                <a class="assimilation" onclick="showAssimilationDropDown()">
                    <span class="material-symbols-outlined">communication</span>
                    <h2>Assimilation</h2>
                </a>
                <div class="dropdown-content assimilationDropdown">
                    <a href="../assimilation_admin/dashboard.php">Dashboard</a>
                    <a href="../assimilation_admin/attendance.php">Attendance</a>
                    <a href="../assimilation_admin/history.php">Attendance History</a>
                    <a href="../assimilation_admin/visitors.php">Visitors</a>
                </div>
            </div>

            <!-- Finance Dropdown -->
            <div class="dropdown">
                <a class="finance" onclick="showFinanceDropDown()">
                    <span class="material-symbols-outlined">payments</span>
                    <h2>Finance</h2>
                </a>
                <div class="dropdown-content financeDropdown">
                    <a href="../finance_admin/dashboard.php">Dashboard</a>
                    <a href="../finance_admin/donations.php">Online Donations</a>
                    <a href="../finance_admin/transactions.php">Collections & Expenses</a>
                </div>
            </div>

            <a href="../main_admin/events.php">
                <span class="material-symbols-outlined">event</span>
                <h2>Events</h2>
            </a>

        <?php } ?>

        <!-- Membership Admin: No dropdown, just direct links -->
        <?php if ($_SESSION['role'] === 'membership_admin') { ?>
            <a href="../membership_admin/dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h2>Dashboard</h2>
            </a>
            <a href="../membership_admin/attendance.php">
                <span class="material-symbols-outlined">check_circle</span>
                <h2>Attendance</h2>
            </a>
            <a href="../membership_admin/history.php">
                <span class="material-symbols-outlined">history</span>
                <h2>Attendance History</h2>
            </a>
            <a href="../membership_admin/members.php">
                <span class="material-symbols-outlined">people</span>
                <h2>Members</h2>
            </a>
        <?php } ?>

        <!-- Assimilation Admin: No dropdown, just direct links -->
        <?php if ($_SESSION['role'] === 'assimilation_admin') { ?>
            <a href="../assimilation_admin/dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h2>Dashboard</h2>
            </a>
            <a href="../assimilation_admin/attendance.php">
                <span class="material-symbols-outlined">check_circle</span>
                <h2>Attendance</h2>
            </a>
            <a href="../assimilation_admin/history.php">
                <span class="material-symbols-outlined">history</span>
                <h2>Attendance History</h2>
            </a>
            <a href="../assimilation_admin/visitors.php">
                <span class="material-symbols-outlined">people_outline</span>
                <h2>Visitors</h2>
            </a>
        <?php } ?>

        <!-- Finance Admin: No dropdown, just direct links -->
        <?php if ($_SESSION['role'] === 'finance_admin') { ?>
            <a href="../finance_admin/dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h2>Dashboard</h2>
            </a>
            <a href="../finance_admin/donations.php">
                <span class="material-symbols-outlined">credit_card</span>
                <h2>Online Donations</h2>
            </a>
            <a href="../finance_admin/transactions.php">
                <span class="material-symbols-outlined">payment</span>
                <h2>Collections & Expenses</h2>
            </a>
        <?php } ?>

        <a href="../../logout.php">
            <span class="material-symbols-outlined">logout</span>
            <h2>Logout</h2>
        </a>
    </div>
</aside>

<script>
    function showMembershipDropDown() {
        const membershipDropdown = document.querySelector('.membershipDropdown');
        membershipDropdown.style.display = (membershipDropdown.style.display !== "block") ? "block" : "none";
    }

    function showFinanceDropDown() {
        const financeDropdown = document.querySelector('.financeDropdown');
        financeDropdown.style.display = (financeDropdown.style.display !== "block") ? "block" : "none";
    }

    function showAssimilationDropDown() {
        const assimilationDropdown = document.querySelector('.assimilationDropdown');
        assimilationDropdown.style.display = (assimilationDropdown.style.display !== "block") ? "block" : "none";
    }
</script>
