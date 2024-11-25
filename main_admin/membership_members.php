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
          // Connect to database
          $conn = new mysqli('localhost', 'root', '', 'cbc_ims');
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Capture form data
          $full_name = $_POST['full_name'];
          $age = $_POST['age'];
          $address = $_POST['address'];
          $email = $_POST['email'];
          $sex = $_POST['sex'];
          $birthdate = $_POST['birthdate'];
          $contact_number = $_POST['contact_number'];

          // Insert data into the database
          $query = "INSERT INTO members (full_name, age, address, email, sex, birthdate, contact_number)
                    VALUES ('$full_name', '$age', '$address', '$email', '$sex', '$birthdate', '$contact_number')";

        if ($conn->query($query) === TRUE) {
          echo "<script>
              alert('Member added successfully!');
              // Close the modal
              document.getElementById('modal-form').style.display = 'none';
              // Optionally, refresh the list or dynamically add the member to the table if you prefer no reload
              location.reload(); // Or you can manually add the new member's row to the table if you prefer no reload
          </script>";
        } else {
          echo "<script>alert('Error: " . $conn->error . "');</script>";
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
            <th>Age</th>
            <th>Address</th>
            <th>Email</th>
            <th>Sex</th>
            <th>Birthdate</th>
            <th>Contact No.</th>
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
                  echo "<tr data-id='{$row['id']}' data-fullname='{$row['full_name']}' data-age='{$row['age']}'
                          data-address='{$row['address']}' data-email='{$row['email']}' data-sex='{$row['sex']}'
                          data-birthdate='{$row['birthdate']}' data-contact='{$row['contact_number']}' >
                          <td>{$row['id']}</td>
                          <td>{$row['full_name']}</td>
                          <td>{$row['age']}</td>
                          <td>{$row['address']}</td>
                          <td>{$row['email']}</td>
                          <td>{$row['sex']}</td>
                          <td>{$row['birthdate']}</td>
                          <td>{$row['contact_number']}</td>
                          <td>
                          <button class='view-btn'>View</button>
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
            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required>
            <label for="contact_number">Contact Number:</label>
            <input type="tel" id="contact_number" name="contact_number" required>
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
          <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
          <p><strong>Contact No.:</strong> <span id="view-contact"></span></p>
        </div>
      </div>

      <!-- JavaScript for modals and view/delete actions -->
      <script>
        document.getElementById("add-member-btn").addEventListener("click", function() {
          document.getElementById("modal-form").style.display = "block";
        });

        document.getElementById("closeModal").addEventListener("click", function() {
          document.getElementById("modal-form").style.display = "none";
        });

        document.getElementById("closeViewModal").addEventListener("click", function() {
          document.getElementById("view-modal").style.display = "none";
        });

        document.addEventListener("DOMContentLoaded", function() {
          document.querySelectorAll(".view-btn").forEach(button => {
            button.addEventListener("click", function() {
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
        });
      </script>
    </main>
  </div>
</body>
</html>
