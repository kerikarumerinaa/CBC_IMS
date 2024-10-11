<!-- /includes/sidebar.php -->
<link rel="stylesheet" href="../assets/sidebar.css"> <!-- Adjust path as needed -->


<aside>
    <div class="top">
        <div class="logo">
            <img src="../assets/cbc-logo.png" alt="CBC Logo"> <!-- Adjust path if needed -->
        </div>
    </div>
    <div class="sidebar">
        <a href="dashboard.php" class="">
            <span class="material-symbols-outlined">dashboard</span>
            <h2>Dashboard</h2>
        </a>
        <div class="dropdown">
            <a class="membership" onclick="showMembershipDropDown()">
                <span class="material-symbols-outlined">groups</span>
                <h2>Membership</h2>
            </a>
            <div class="dropdown-content membershipDropdown">
                <a href="membership_dashboard.php">Dashboard</a>
                <a href="membership_attendance.php">Attendance</a>
                <a href="membership_members.php">Members</a>
            </div>
        </div>
        <div class="dropdown">
            <a class="finance" onclick="showFinanceDropDown()">
                <span class="material-symbols-outlined">payments</span>
                <h2>Finance</h2>
            </a>
            <div class="dropdown-content financeDropdown">
                <a href="finance_transactions.php">Transactions</a>
                <a href="finance_dashboard.php">Dashboard</a>
            </div>
        </div>
        <a href="events.php">
            <span class="material-symbols-outlined">event</span>
            <h2>Events</h2>
        </a>
        <a href="../logout.php">
            <span class="material-symbols-outlined">logout</span>
            <h2>Logout</h2>
        </a>
    </div>
</aside>


    
<script>
    function showMembershipDropDown(){
        const membershipDropdown = document.querySelector('.membershipDropdown');
        if(membershipDropdown.style.display !== "flex"){
            console.log(membershipDropdown.style.display)
            membershipDropdown.style.display = "flex";
        }
        else{
            console.log(membershipDropdown.style.display)
            membershipDropdown.style.display = "none";
            
        }
    }
    function showFinanceDropDown(){
        const membershipDropdown = document.querySelector('.financeDropdown');
        if(membershipDropdown.style.display !== "flex"){
            console.log(membershipDropdown.style.display)
            membershipDropdown.style.display = "flex";
        }
        else{
            console.log(membershipDropdown.style.display)
            membershipDropdown.style.display = "none";
            
        }
    }

</script> <!-- Include your JavaScript file -->



