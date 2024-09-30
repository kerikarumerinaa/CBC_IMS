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
    <!-------------------------------------------MAIN--------------------------------------->
    <main>
      <!-------------------------------------------MEMBER LIST------------------------------------->
      <h2>Member List</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Join Date</th>
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
                          <td>{$row['email']}</td>
                          <td>{$row['phone']}</td>
                          <td>{$row['status']}</td>
                          <td>{$row['join_date']}</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='6'>No members found</td></tr>";
          }

          // Close connection
          $conn->close();
          ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
