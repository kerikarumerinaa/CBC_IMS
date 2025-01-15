<?php
session_start();

// Restrict access if not logged in as a member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "cbc_ims");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch member details based on session data (using email stored in session)
if (isset($_SESSION['email'])) {
$email = $_SESSION['email']; // Assuming email is stored in session after login
$sql = "SELECT * FROM users_members WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // Use the email to fetch user details
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
} else {
    $user = null;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Security</title>
    <link rel="stylesheet" href="security.css"> 
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="security-content">
        <section class="security-section">
            <h4>Account Security</h4>
            <div class="security-details">

                <!-- Email Section -->
                <div class="info-item">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <!-- Password Change Section -->
                <div class="info-item">
                    <label for="password">Password</label>
                    <button class="change-password-btn" onclick="openChangePasswordModal()">Change Password</button>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" data-close="changePasswordModal">&times;</span>
        <h2>Change Password</h2>
        <form action="changepassword.php" method="POST">
            <label for="currentPassword">Current Password</label>
            <input type="password" id="currentPassword" name="current_password" required>

            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword" name="new_password" required>

            <label for="confirmPassword">Confirm New Password</label>
            <input type="password" id="confirmPassword" name="confirm_password" required>

         <input type="submit" value="Save">
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const changePasswordModal = document.getElementById('changePasswordModal');
        const openModalButton = document.querySelector('.change-password-btn');
        const closeButtons = document.querySelectorAll('[data-close]');

        // Open modal when clicking "Change Password" button
        openModalButton.addEventListener('click', function() {
            changePasswordModal.style.display = 'block';
        });

        // Close modal when clicking the "X" button
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = button.getAttribute('data-close');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });
    });
</script>

</body>
</html>
