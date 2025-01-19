<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=notifications" />

<nav class="navbar">
    <div class="navbar-content">
        <img src="../assets/cbc-logo.png" alt="Member Avatar" class="logo">
        <div class="account">
            <h2><?php echo $_SESSION['username']; ?></h2>
            <?php
                $sql = "SELECT role FROM users_members WHERE fullname = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                $member = $result->fetch_assoc();
                $stmt->close();
            ?>
            <p><?php echo $member['role']; ?></p> 
        </div>
    </div>
    <div class="hamburger" onclick="toggleMenu()">☰</div>
    <div class="menu">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="profile_details.php">My Profile</a></li>
            <li><a href="donation.php">Donate</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>


<script>
    function toggleMenu() {
        const menu = document.querySelector('.menu');
        menu.classList.toggle('active');
    }
</script>


