<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=notifications" />

<nav class="navbar">
    <div class="navbar-content">
        <img src="../assets/cbc-logo.png" alt="Member Avatar" class="logo">
        <div class="account">
            <h2>Kleyr Carmelina</h2>
            <p>team.hello@com</p>
        </div>
    </div>
    <div class="menu">
        <ul>
            <li><a href="profile_details.php">My Profile</a></li>
            <li><a href="donation.php">My Donations</a></li>
            <li><a href="security.php">My Security</a></li>
            <li><a href="../logout.php">Logout</a></li>
            <li class="notification-item">
                <a href="#" class="notification-icon">
                    <span class="material-symbols-outlined">notifications</span>
                </a>
                <!-- Dropdown content -->
                <div class="notification-dropdown">
                    <ul>
                        <li><a href="#">No upcoming events</a></li>
                        
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.querySelector('.notification-icon').addEventListener('click', function(event) {
        event.preventDefault();
        const dropdown = document.querySelector('.notification-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close the dropdown if clicked outside of it
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.notification-item')) {
            const dropdown = document.querySelector('.notification-dropdown');
            dropdown.style.display = 'none';
        }
    });
</script>

