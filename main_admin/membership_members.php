<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="membership_members.css">
</head>
<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <main>
      <div class="members-header">
        <h2>Member List</h2>
        <button id="add-member-btn">Add Member</button>
      </div>

      <!-- PHP code to handle Add Member form submission -->
      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Add or Update logic
        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            // Update Member logic
            $id = $_POST['edit_id'];
            $full_name = $_POST['edit_full_name'];
            $age = $_POST['edit_age'];
            $address = $_POST['edit_address'];
            $email = $_POST['edit_email'];
            $sex = $_POST['edit_sex'];
            $network = $_POST['edit_network'];
            $birthdate = $_POST['edit_birthdate'];
            $contact_number = $_POST['edit_contact_number'];

            $update_query = "UPDATE members 
                            SET full_name = ?, age = ?, address = ?, email = ?, sex = ?, network = ?, birthdate = ?, contact_number = ? 
                            WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('sissssssi', $full_name, $age, $address, $email, $sex, $network, $birthdate, $contact_number, $id);

            if ($stmt->execute()) {
                echo "<script>alert('Member updated successfully!'); window.location.href='membership_members.php';</script>";
            } else {
                echo "<script>alert('Error updating member: " . $conn->error . "');</script>";
            }
            $stmt->close();
        } else {
            // Add new Member logic
            $full_name = $_POST['full_name'];
            $age = $_POST['age'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $sex = $_POST['sex'];
            $network = $_POST['network'];
            $birthdate = $_POST['birthdate'];
            $contact_number = $_POST['contact_number'];

            $query = "INSERT INTO members (full_name, age, address, email, sex, network, birthdate, contact_number) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sissssss', $full_name, $age, $address, $email, $sex, $network, $birthdate, $contact_number);

            if ($stmt->execute()) {
                echo "<script>alert('Member added successfully!'); window.location.href='membership_members.php';</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
            $stmt->close();
        }

        $conn->close();
    }

    // Handle deletion
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $delete_query = "DELETE FROM members WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $delete_id);
        if ($stmt->execute()) {
            echo "<script>alert('Member deleted successfully'); window.location.href='membership_members.php';</script>";
        } else {
            echo "<script>alert('Error deleting member');</script>";
        }

        $stmt->close();
        $conn->close();
    }

    ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Network</th>
            <th>Birthdate</th>
            <th>Contact Number</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }
          $query = "SELECT * FROM members";
          $result = $conn->query($query);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr data-id='{$row['id']}' data-fullname='{$row['full_name']}' data-age='{$row['age']}' data-address='{$row['address']}' data-email='{$row['email']}'
                          data-sex='{$row['sex']}' data-network='{$row['network']}' data-birthdate='{$row['birthdate']}' data-contact='{$row['contact_number']}' >
                          <td>{$row['id']}</td>
                          <td>{$row['full_name']}</td>
                          <td>{$row['address']}</td>
                          <td>{$row['email']}</td>
                          <td>{$row['sex']}</td>
                          <td>{$row['age']}</td>
                          <td>{$row['network']}</td>
                          <td>{$row['birthdate']}</td>
                          <td>{$row['contact_number']}</td>
                          <td>
                          <button class='view-btn'>View</button>
                          <button class='edit-btn'>Edit</button>
                          <a href='membership_members.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this member?\")'><button class='delete-btn'>Delete</button></a>
                          </td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='9'>No members found</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>

      <!-- Add Member Modal -->
      <div id="modal-form" class="modal">
        <div class="modal-content">
          <span id="closeModal" class="close">&times;</span>
          <h2>Add Member</h2>
          <form method="POST" action="">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="sex">Sex:</label>
            <select id="sex" name="sex">
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <label for="network">Network:</label>
            <select id="network" name="network">
              <option value="none">None</option>
              <option value="youth">Youth</option>
              <option value="singles">Singles</option>
              <option value="women">Couples/Women</option>
              <option value="men">Couples/Men</option>
              <option value="senior">Senior Folks</option>
            </select>
            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required>
            <label for="contact_number">Contact Number:</label>
            <input type="number" id="contact_number" name="contact_number" required>
            <input type="submit" value="Add Member">
          </form>
        </div>
      </div>

      <!-- View Member Modal -->
      <div id="view-modal" class="modal">
        <div class="modal-content">
          <span id="closeViewModal" class="close">&times;</span>
          <h2>View Member</h2>
          <p><strong>Full Name:</strong> <span id="view-full-name"></span></p>
          <p><strong>Age:</strong> <span id="view-age"></span></p>
          <p><strong>Address:</strong> <span id="view-address"></span></p>
          <p><strong>Email:</strong> <span id="view-email"></span></p>
          <p><strong>Sex:</strong> <span id="view-sex"></span></p>
          <p><strong>Network:</strong> <span id="view-network"></span></p>
          <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
          <p><strong>Contact No.:</strong> <span id="view-contact"></span></p>
        </div>
      </div>

      <!-- Edit Member Modal -->
      <div id="edit-modal" class="modal">
        <div class="modal-content">
          <span id="closeEditModal" class="close">&times;</span>
          <h2>Edit Member</h2>
          <form method="POST" action="membership_members.php">
            <input type="hidden" id="edit-id" name="edit_id">
            <label for="edit-full-name">Full Name:</label>
            <input type="text" id="edit-full-name" name="edit_full_name" required>
            <label for="edit-age">Age:</label>
            <input type="number" id="edit-age" name="edit_age" required>
            <label for="edit-address">Address:</label>
            <input type="text" id="edit-address" name="edit_address" required>
            <label for="edit-email">Email:</label>
            <input type="email" id="edit-email" name="edit_email" required>
            <label for="edit-sex">Sex:</label>
            <select id="edit-sex" name="edit_sex">
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <label for="edit-network">Network:</label>
            <select id="edit-network" name="edit_network">
              <option value="none">None</option>
              <option value="youth">Youth</option>
              <option value="singles">Singles</option>
              <option value="women">Couples/Women</option>
              <option value="men">Couples/Men</option>
              <option value="senior">Senior Folks</option>
            </select>
            <label for="edit-birthdate">Birthdate:</label>
            <input type="date" id="edit-birthdate" name="edit_birthdate" required>
            <label for="edit-contact-number">Contact Number:</label>
            <input type="tel" id="edit-contact-number" name="edit_contact_number" required>
            <input type="submit" value="Update Member">
          </form>
        </div>
      </div>

      


      <!-- JavaScript for modals and view/delete actions -->
      <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add Member Modal
            document.getElementById("add-member-btn").addEventListener("click", function () {
              document.getElementById("modal-form").style.display = "block";
            });

            document.getElementById("closeModal").addEventListener("click", function () {
              document.getElementById("modal-form").style.display = "none";
            });

            // View Member Modal
            document.querySelectorAll(".view-btn").forEach(button => {
              button.addEventListener("click", function () {
                const row = this.closest("tr");
                document.getElementById("view-full-name").textContent = row.dataset.fullname;
                document.getElementById("view-age").textContent = row.dataset.age;
                document.getElementById("view-address").textContent = row.dataset.address;
                document.getElementById("view-email").textContent = row.dataset.email;
                document.getElementById("view-sex").textContent = row.dataset.sex;
                document.getElementById("view-birthdate").textContent = row.dataset.birthdate;
                document.getElementById("view-contact").textContent = row.dataset.contact;
                document.getElementById("view-modal").style.display = "block";
              });
            });

            document.getElementById("closeViewModal").addEventListener("click", function () {
              document.getElementById("view-modal").style.display = "none";
            });

            // Edit Member Modal
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                const row = this.closest("tr");

                // Populate the Edit Modal with the member's data
                document.getElementById("edit-id").value = row.dataset.id;
                document.getElementById("edit-full-name").value = row.dataset.fullname;
                document.getElementById("edit-age").value = row.dataset.age;
                document.getElementById("edit-address").value = row.dataset.address;
                document.getElementById("edit-email").value = row.dataset.email;
                document.getElementById("edit-sex").value = row.dataset.sex;
                document.getElementById("edit-network").value = row.dataset.network;
                document.getElementById("edit-birthdate").value = row.dataset.birthdate;
                document.getElementById("edit-contact-number").value = row.dataset.contact;

                // Show the Edit Modal
                document.getElementById("edit-modal").style.display = "block";
            });
        });

        // Close Edit Modal
        document.getElementById("closeEditModal").addEventListener("click", function () {
            document.getElementById("edit-modal").style.display = "none";
        });
      });
      </script>
    </main>
  </div>
</body>
</html>
