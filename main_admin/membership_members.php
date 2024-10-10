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

  <!-- Include the sidebar -->
  <?php include '../includes/sidebar.php'; ?>
  <?php include '../includes/db_connection.php'; ?>
    <!-------------------------------------------MAIN--------------------------------------->
    <main>
      <!-------------------------------------------MEMBER LIST------------------------------------->
      <h2>Member List</h2>
      <button id="add-member-btn">Add Member</button>
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
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Database connection
          $conn = new mysqli('localhost', 'root', '', 'cbc_ims');

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Fetch members from the database
          $query = "SELECT * FROM members";
          $result = $conn->query($query);

          // Check if there are results and display them
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['id']}</td>
                          <td>{$row['name']}</td>
                          <td>{$row['age']}</td>
                          <td>{$row['address']}</td>
                          <td>{$row['email']}</td>
                          <td>{$row['sex']}</td>
                          <td>{$row['birthdate']}</td>  
                          <td>{$row['phone']}</td>
                          <td>{$row['status']}</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='9'>No members found</td></tr>";
          }

          // Close connection
          $conn->close();
          ?>
        </tbody>
      </table>

         <!-- Modal form -->
         <div id="modal-form" class="modal">
        <div class="modal-content">
          <h2>Add Member</h2>
          <form method="POST" action="">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full-name" required><br><br>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required><br><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="sex">Sex:</label>
            <select id="sex" name="sex">
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select><br><br>
            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required><br><br>
            <label for="contact-number">Contact Number:</label>
            <input type="tel" id="contact-number" name="contact-number" required><br><br>
            <label for="status">Status:</label>
            <select id="status" name="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select><br><br>
            <input type="submit" value="Add Member">
          </form>
        </div>
      </div>


      <?php
// Database connection details
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "cbc_ims"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $age = (int)$_POST['age'];
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = $conn->real_escape_string($_POST['status']);

    // SQL query to insert data into the members table
    $sql = "INSERT INTO members (name, age, address, email, sex, birthdate, phone, status)
            VALUES ('$name', $age, '$address', '$email', '$sex', '$birthdate', '$phone', '$status')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "New member added successfully";
        // Redirect to the members list or attendance page after successful insertion
        header("Location: membership_members.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>




      <script>
        document.getElementById("add-member-btn").addEventListener("click", function() {
          document.getElementById("modal-form").style.display = "block";
        });

        window.addEventListener("click", function(event) {
          if (event.target == document.getElementById("modal-form")) {
            document.getElementById("modal-form").style.display = "none";
          }
        });
      </script>
    </main>
  </div>
</body>
</html>
