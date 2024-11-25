<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitors</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assimilation_visitors.css">
</head>
<body>
    <div class="container">
        <?php 
        include '../includes/sidebar.php'; 
        include '../includes/db_connection.php'; 

        // Add Visitor PHP logic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = $_POST['full_name'];
            $age = $_POST['age'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $sex = $_POST['sex'];
            $birthdate = $_POST['birthdate'];
            $contact_number = $_POST['contact_number'];
            $network = $_POST['network'];
            $date_of_visit = $_POST['date'];
            $invited_by = $_POST['invited_by'];
            $assimilated_by = $_POST['assimilated_by'];

            $query = "INSERT INTO visitors (full_name, age, address, email, sex, birthdate, contact_number, network, date_of_visit, invited_by, assimilated_by) 
                      VALUES ('$full_name', '$age', '$address', '$email', '$sex', '$birthdate', '$contact_number', '$network', '$date_of_visit', '$invited_by', '$assimilated_by')";

                if ($conn->query($query) === TRUE) {
                    echo "<script>
                        alert('Visitor added successfully!');
                        // Close the modal
                        document.getElementById('addVisitorModal').classList.remove('show');
                        document.getElementById('addVisitorModal').style.display = 'none';
                        document.body.classList.remove('modal-open');
                        document.querySelector('.modal-backdrop').remove();
                        window.location.reload();
                    </script>";
                } else {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
                }
            }

            if (isset($_GET['delete_id'])) {
                $visitorId = $_GET['delete_id'];
                $query = "DELETE FROM visitors WHERE id = $visitorId";
                
                if ($conn->query($query) === TRUE) {
                    echo "<script>
                            alert('Visitor deleted successfully!');
                            window.location.href = 'assimilation_visitors.php';
                          </script>";
                } else {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
                }
            }
            ?>
             

        <main>
            <div class="assimilation-header">
                <h2>Visitors List</h2>
                <button id="add-visitor-btn">Add Visitor</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Contact Number</th>
                        <th>Network</th>
                        <th>Date Of Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM visitors";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr data-id='{$row['id']}' 
                                      data-fullname='{$row['full_name']}' 
                                      data-age='{$row['age']}'
                                      data-address='{$row['address']}' 
                                      data-email='{$row['email']}' 
                                      data-sex='{$row['sex']}'
                                      data-birthdate='{$row['birthdate']}' 
                                      data-contact='{$row['contact_number']}' 
                                      data-network='{$row['network']}'
                                      data-date_of_visit='{$row['date_of_visit']}' 
                                      data-invited_by='{$row['invited_by']}' 
                                      data-assimilated_by='{$row['assimilated_by']}'>
                                <td>{$row['id']}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['sex']}</td>
                                <td>{$row['contact_number']}</td>
                                <td>{$row['network']}</td>
                                <td>{$row['date_of_visit']}</td>
                                <td>
                                    <button class='view-btn'>View</button>
                                     <a href='assimilation_visitors.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this visitor?\")'><button class='delete-btn'>Delete</button></a>
                                    </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No visitors found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Add Visitor Modal -->
            <div id="visitor-modal" class="modal">
                <div class="modal-content">
                    <span id="closeVisitorModal" class="close">&times;</span>
                    <h2>Add Visitor</h2>
                    <form method="POST">
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

                        <label for="network">Network:</label>
                        <select id="network" name="network">
                            <option value="none">None</option>
                            <option value="youth">Youth</option>
                            <option value="singles">Singles</option>
                            <option value="women">Couples/Women</option>
                            <option value="men">Couples/Men</option>
                            <option value="senior">Senior Folks</option>
                        </select>

                        <label for="date">Date Of Visit:</label>
                        <input type="date" id="date" name="date" required>

                        <label for="invited_by">Invited By:</label>
                        <input type="text" id="invited_by" name="invited_by">

                        <label for="assimilated_by">Assimilated By:</label>
                        <input type="text" id="assimilated_by" name="assimilated_by">

                        <input type="submit" value="Add Visitor">
                    </form>
                </div>
            </div>

            <!-- View Visitor Modal -->
            <div id="view-modal" class="modal">
                <div class="modal-content">
                    <span id="closeViewModal" class="close">&times;</span>
                    <h2>Visitor's Details:</h2>
                    <p><strong>Full Name:</strong> <span id="view-full-name"></span></p>
                    <p><strong>Age:</strong> <span id="view-age"></span></p>
                    <p><strong>Address:</strong> <span id="view-address"></span></p>
                    <p><strong>Email:</strong> <span id="view-email"></span></p>
                    <p><strong>Sex:</strong> <span id="view-sex"></span></p>
                    <p><strong>Birthdate:</strong> <span id="view-birthdate"></span></p>
                    <p><strong>Contact:</strong> <span id="view-contact"></span></p>
                    <p><strong>Network:</strong> <span id="view-network"></span></p>
                    <p><strong>Date Of Visit:</strong> <span id="view-date"></span></p>
                    <p><strong>Invited By:</strong> <span id="view-invited-by"></span></p>
                    <p><strong>Assimilated By:</strong> <span id="view-assimilated-by"></span></p>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById("add-visitor-btn").addEventListener("click", function() {
            document.getElementById("visitor-modal").style.display = "block";
        });

        document.getElementById("closeVisitorModal").addEventListener("click", function() {
            document.getElementById("visitor-modal").style.display = "none";
        });

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
                document.getElementById("view-network").textContent = row.dataset.network;
                document.getElementById("view-date").textContent = row.dataset.date_of_visit;
                document.getElementById("view-invited-by").textContent = row.dataset.invited_by;
                document.getElementById("view-assimilated-by").textContent = row.dataset.assimilated_by;

                document.getElementById("view-modal").style.display = "block";
            });
        });

        document.getElementById("closeViewModal").addEventListener("click", function() {
            document.getElementById("view-modal").style.display = "none";
        });
    </script>
</body>
</html>
