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
                    <!-- <div class="profile-card">
                        <div class="profile-info">
                            <img src="../assets/cbc-logo.png" alt="User Avatar" class="user-avatar">
                            <div class="user-info">
                            </div>
                        </div>
                        <button class="edit-btn" data-modal="editProfileModal">Edit Photo</button>
                    </div> -->

                    <!-- Personal Information Section -->
                    <div class="personal-info">
                        <h5>Personal Information</h5>
                        <div class="info-item">
                            <p>Full Name:</p>
                            <p><?php echo $_SESSION['username']; ?></p>
                           
                        </div>

                        <div class="info-item">
                            <p>Email Address:</p>
                            <p><?php
                                $sql = "SELECT email FROM users_members WHERE fullname = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $_SESSION['username']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $user = $result->fetch_assoc();
                                echo $user['email'];
                            ?></p>   
                        </div>

                        <div class="info-item">
                            <p>Password:</p>
                            <p><?php
                                $sql = "SELECT password FROM users_members WHERE fullname = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $_SESSION['username']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $member = $result->fetch_assoc();
                                echo "********";
                            ?></p>
                        </div>
                        <div class="info-item">
                            <p>Change Password:</p>
                            <button class="edit-btn" data-modal="changePasswordModal">Change</button>
                        </div>
                            
                        
                    </div>
                        <button class="edit-btn" data-modal="editPersonalInfoModal">Edit</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal: Change Password -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" data-close="changePasswordModal">&times;</span>
            <h2>Change Password</h2>
            <form action="update_password.php" method="POST">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>

                <button type="submit">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Personal Information -->
    <div id="editPersonalInfoModal" class="modal">
        <div class="modal-content">
            <span class="close" data-close="editPersonalInfoModal">&times;</span>
            <h2>Edit Personal Information</h2>
            <form method="POST">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php
                    $sql = "SELECT email FROM users_members WHERE fullname = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $_SESSION['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    echo htmlspecialchars($user['email']);
                ?>" required>
                
                <input type="hidden" name="old_email" value="<?php echo htmlspecialchars($user['email']); ?>">
                
                

                <input type="submit" value="Save" name="save">
                
                <!-- BACKEND FOR UPDATE INFO -->
                <?php
                if (isset($_POST['save'])) {
                    $fullname = $_POST['fullname'];
                    $email = $_POST['email'];
                    $old_email = $_POST['old_email'];

                    $sql = "UPDATE users_members SET fullname = ?, email = ? WHERE email = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $fullname, $email, $old_email);

                    if ($stmt->execute()) {
                        $_SESSION['username'] = $fullname;
                        echo "<script>alert('Information Updated');</script>";
                    } else {
                        echo "<script>alert('Error updating information');</script>";
                    }
                }
                ?>
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
