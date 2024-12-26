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
                            
                            <p>Member</p>
                        </div>
                    </div>
                    <button class="edit-btn" data-modal="editProfileModal">Edit Photo</button>
                </div>

                <!-- Personal Information Section -->
                <div class="personal-info">
                    <h5>Personal Information</h5>
                    <div class="info-item">
                        <p>First Name:</p>
                        
                    </div>
                    <div class="info-item">
                        <p>Last Name:</p>
                        
                    </div>
                    <div class="info-item">
                        <p>Email Address:</p>
                        
                    </div>
                    <div class="info-item">
                        <p>Phone:</p>
                        
                    </div>
                    <button class="edit-btn" data-modal="editPersonalInfoModal">Edit</button>
                </div>

                <!-- Address Section -->
                <div class="address-info">
                    <h5>Address</h5>
                    <div class="info-item">
                        <p>Country:</p>
                        
                    </div>
                    <div class="info-item">
                        <p>City/State:</p>
                        
                    </div>
                    <button class="edit-btn" data-modal="editAddressModal">Edit</button>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Modals -->
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
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="first_name" value="" required>
            
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="last_name" value="" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="" required>
            
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="" required>
            
            <input type="submit" value="Save">
        </form>
    </div>
</div>

<!-- Modal 3: Edit Address -->
<div id="editAddressModal" class="modal">
    <div class="modal-content">
        <span class="close" data-close="editAddressModal">&times;</span>
        <h2>Edit Address</h2>
        <form action="update_address.php" method="POST">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" value="" required>
            
            <label for="cityState">City/State</label>
            <input type="text" id="cityState" name="city_state" value="" required>
            
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
