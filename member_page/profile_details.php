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
$email = $_SESSION['email']; // Assuming email is stored in session after login
$sql = "SELECT * FROM users_members WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $users_email); // Use the email to fetch user details
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Details</title>
    <link rel="stylesheet" href="profile_details.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="profile-page">
        <main class="profile-content">
            <section class="profile-section">
                <h4>My Profile</h4>
                <div class="profile-details">
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-info">
                            <img src="../assets/cbc-logo.png" alt="User Avatar" class="user-avatar">
                            <div class="user-info">
                                <p><?php echo htmlspecialchars($user['fullname']); ?></p>
                            </div>
                        </div>
                        <button class="edit-btn" data-modal="editProfileModal">Edit Photo</button>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="personal-info">
                        <h5>Personal Information</h5>
                        <div class="info-item">
                            <p>Full Name:</p>
                            <p><?php echo htmlspecialchars($member['fullname']); ?></p>
                        </div>
                        <div class="info-item">
                            <p>Email Address:</p>
                            <p><?php echo htmlspecialchars($member['email']); ?></p>
                        </div>
                        </div>
                        <button class="edit-btn" data-modal="editPersonalInfoModal">Edit</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modals (for editing profile details) -->
    <!-- Modal 1: Edit Profile Avatar -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" data-close="editProfileModal">&times;</span>
            <h2>Edit Profile Avatar</h2>
            <form enctype="multipart/form-data" action="update_avatar.php" method="POST">
                <label for="avatar">Upload New Avatar</label>
                <input type="file" id="avatar" name="avatar" required>
                <input type="submit" value="Save">
            </form>
        </div>
    </div>

    <!-- Modal 2: Edit Personal Information -->
    <div id="editPersonalInfoModal" class="modal">
        <div class="modal-content">
            <span class="close" data-close="editPersonalInfoModal">&times;</span>
            <h2>Edit Personal Information</h2>
            <form action="update_personal_info.php" method="POST">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <input type="submit" value="Save">
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        const modals = document.querySelectorAll('.modal');
        const closeButtons = document.querySelectorAll('.close');

        // Open modal when clicking edit button
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = button.getAttribute('data-modal');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'block';
                }
            });
        });

        // Close modal when clicking the close button
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = button.getAttribute('data-close');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });
        
        // Close modal on outside click
        window.onclick = function(event) {
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        };
    });
    </script>

</body>
</html>
