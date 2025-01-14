<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'assimilation_admin' && $_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>

<!-- SEARCH FUNCTION -->
<?php
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Establish connection
$conn = new mysqli('localhost', 'root', '', 'cbc_ims');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify query to include search filter
$query = "SELECT * FROM visitors";
if (!empty($search_query)) {
    $query .= " WHERE full_name LIKE ? OR email LIKE ? OR address LIKE ?";
}

$stmt = $conn->prepare($query);
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param('sss', $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- EDIT FUNCTION -->
<?php
// Edit Visitor PHP logic
if (isset($_POST['edit_visitor'])) {
    $visitor_id = $_POST['visitor_id'];
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

    $query = "UPDATE visitors SET 
                full_name='$full_name', 
                age='$age', 
                address='$address', 
                email='$email', 
                sex='$sex', 
                birthdate='$birthdate', 
                contact_number='$contact_number', 
                network='$network', 
                date_of_visit='$date_of_visit', 
                invited_by='$invited_by', 
                assimilated_by='$assimilated_by' 
              WHERE id='$visitor_id'";

    if ($conn->query($query) === TRUE) {
        echo "<script>
                alert('Visitor updated successfully!');
                window.location.href = 'visitors.php';
              </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<?php
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
                            window.location.href = 'visitors.php';
                          </script>";
                } else {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
                }
            }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitors</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="visitors.css">
</head>
<body>
    <div class="container">
        <?php 
        include '../../includes/sidebar.php'; 
        include '../../includes/db_connection.php'; 
        ?>

        <main>
            <div class="assimilation-header">
                <h2>Visitors List</h2>
                <form method="GET" action="visitors.php">
                    <input type="text" placeholder="Search by name, age, or network" class="search-bar" id="search-visitor" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
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
                        <th>Status</th>
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
                                    <button class='edit-btn'>Edit</button>
                                     <a href='visitors.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this visitor?\")'><button class='delete-btn'>Delete</button></a>  
                                    </td>
                                    <td>
                                    <button class='status-btn'>Checklist</button>
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


            <!-- Edit Visitor Modal -->
            <div id="edit-modal" class="modal">
                <div class="modal-content">
                    <span id="closeEditModal" class="close">&times;</span>
                    <h2>Edit Visitor</h2>
                    <form method="POST">
                        <input type="hidden" id="visitor-id" name="visitor_id">
                        
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="edit-full-name" name="full_name" required>
                        
                        <label for="age">Age:</label>
                        <input type="number" id="edit-age" name="age" required>
                        
                        <label for="address">Address:</label>
                        <input type="text" id="edit-address" name="address" required>
                        
                        <label for="email">Email:</label>
                        <input type="email" id="edit-email" name="email" required>
                        
                        <label for="sex">Sex:</label>
                        <select id="edit-sex" name="sex">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        
                        <label for="birthdate">Birthdate:</label>
                        <input type="date" id="edit-birthdate" name="birthdate" required>
                        
                        <label for="contact_number">Contact Number:</label>
                        <input type="tel" id="edit-contact-number" name="contact_number" required>
                        
                        <label for="network">Network:</label>
                        <select id="edit-network" name="network">
                            <option value="none">None</option>
                            <option value="youth">Youth</option>
                            <option value="singles">Singles</option>
                            <option value="women">Couples/Women</option>
                            <option value="men">Couples/Men</option>
                            <option value="senior">Senior Folks</option>
                        </select>
                        
                        <label for="date">Date Of Visit:</label>
                        <input type="date" id="edit-date" name="date" required>
                        
                        <label for="invited_by">Invited By:</label>
                        <input type="text" id="edit-invited-by" name="invited_by">
                        
                        <label for="assimilated_by">Assimilated By:</label>
                        <input type="text" id="edit-assimilated-by" name="assimilated_by">
                        
                        <input type="submit" name="edit_visitor" value="Update Visitor">
                    </form>
                </div>
            </div>


            <!-- Checklist Modal -->
                <div id="checklist-modal" class="modal">
                    <div class="modal-content">
                        <span id="closeChecklistModal" class="close" style="cursor: pointer;">&times;</span>
                        <h2>Checklist for <span id="checklist-full-name"></span>:</h2>
                        <form id="checklist-form">
                            <input type="hidden" id="visitor-id" name="id" value="">
                            <p><strong>EBS1:</strong> <input type="checkbox" id="ebs1" name="ebs1"></p>
                            <p><strong>EBS2:</strong> <input type="checkbox" id="ebs2" name="ebs2"></p>
                            <p><strong>NBC:</strong> <input type="checkbox" id="nbc" name="nbc"></p>
                            <p><strong>Church Recognition:</strong> <input type="checkbox" id="church-recognition" name="church_recognition"></p>
                            <p><strong>Baptism:</strong> <input type="checkbox" id="baptism" name="baptism"></p>
                            <button type="submit" id="save-checklist" class="btn">Save</button>
                        </form>
                    </div>
                </div>


            <!-- Checklist Modal
            <div id="checklist-modal" class="modal">
                        <div class="modal-content">
                            <span id="closeChecklistModal" class="close">&times;</span>
                            <h2>Checklist for <span id="checklist-full-name"></span>:</h2>
                            <p><strong>EBS1:</strong> <input type="checkbox" id="ebs1" name="ebs1"></p>
                            <p><strong>EBS2:</strong> <input type="checkbox" id="ebs2" name="ebs2"></p>
                            <p><strong>NBC:</strong> <input type="checkbox" id="nbc" name="nbc"></p>
                            <p><strong>Church Recognition:</strong> <input type="checkbox" id="church-recognition" name="church_recognition"></p>
                            <p><strong>Baptism:</strong> <input type="checkbox" id="baptism" name="baptism"></p>
                        </div>
                    </div> -->


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

        // Handle Edit Button Click
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const row = this.closest("tr");

                document.getElementById("visitor-id").value = row.dataset.id;
                document.getElementById("edit-full-name").value = row.dataset.fullname;
                document.getElementById("edit-age").value = row.dataset.age;
                document.getElementById("edit-address").value = row.dataset.address;
                document.getElementById("edit-email").value = row.dataset.email;
                document.getElementById("edit-sex").value = row.dataset.sex;
                document.getElementById("edit-birthdate").value = row.dataset.birthdate;
                document.getElementById("edit-contact-number").value = row.dataset.contact;
                document.getElementById("edit-network").value = row.dataset.network;
                document.getElementById("edit-date").value = row.dataset.date_of_visit;
                document.getElementById("edit-invited-by").value = row.dataset.invited_by;
                document.getElementById("edit-assimilated-by").value = row.dataset.assimilated_by;

                document.getElementById("edit-modal").style.display = "block";
            });
        });

// Close Edit Modal
document.getElementById("closeEditModal").addEventListener("click", function() {
    document.getElementById("edit-modal").style.display = "none";
});


        document.querySelectorAll(".status-btn").forEach(button => {
            button.addEventListener("click", function() {
                const row = this.closest("tr");
                document.getElementById("checklist-full-name").textContent = row.dataset.fullname;

                const checklistModal = document.getElementById("checklist-modal");
                const ebs1 = checklistModal.querySelector("#ebs1");
                const ebs2 = checklistModal.querySelector("#ebs2");
                const nbc = checklistModal.querySelector("#nbc");
                const churchRecognition = checklistModal.querySelector("#church-recognition");
                const baptism = checklistModal.querySelector("#baptism");

                // retrieve checklist from database
                const visitorId = row.dataset.id;
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        const checklist = JSON.parse(this.responseText);
                        ebs1.checked = checklist.ebs1;
                        ebs2.checked = checklist.ebs2;
                        nbc.checked = checklist.nbc;
                        churchRecognition.checked = checklist.church_recognition;
                        baptism.checked = checklist.baptism;
                    }
                };
                xhttp.open("GET", `checklist.php?id=${visitorId}`, true);
                xhttp.send();

                checklistModal.style.display = "block";

                // save checklist to database
                const checklistForm = checklistModal.querySelector("form");
                checklistForm.addEventListener("submit", function(event) {
                    event.preventDefault();
                    const xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(this.responseText);
                        }
                    };
                    xhttp.open("POST", "checklist.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send(`id=${visitorId}&ebs1=${ebs1.checked}&ebs2=${ebs2.checked}&nbc=${nbc.checked}&church_recognition=${churchRecognition.checked}&baptism=${baptism.checked}`);
                });
            });
        });
        
        document.getElementById("save-checklist").addEventListener("click", function(event) {
            event.preventDefault();

            const visitorId = document.getElementById("visitor-id").value;
            const ebs1 = document.getElementById("ebs1").checked ? 1 : 0;
            const ebs2 = document.getElementById("ebs2").checked ? 1 : 0;
            const nbc = document.getElementById("nbc").checked ? 1 : 0;
            const churchRecognition = document.getElementById("church-recognition").checked ? 1 : 0;
            const baptism = document.getElementById("baptism").checked ? 1 : 0;

            // Prepare data
            const data = new URLSearchParams();
            data.append("id", visitorId);
            data.append("ebs1", ebs1);
            data.append("ebs2", ebs2);
            data.append("nbc", nbc);
            data.append("church_recognition", churchRecognition);
            data.append("baptism", baptism);

            // Send data to the server
            fetch("checklist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: data.toString(),
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.text();
                })
                .then(result => {
                    alert(result); // Show success message
                    document.getElementById("checklist-modal").style.display = "none"; // Close the modal
                    window.location.reload(); // Reload the page to reflect changes
                })
                .catch(error => {
                    console.error("There was a problem with the fetch operation:", error);
                });
        });


        

        document.getElementById("closeViewModal").addEventListener("click", function() {
            document.getElementById("view-modal").style.display = "none";
        });

        document.getElementById("closeChecklistModal").addEventListener("click", function() {
            document.getElementById("checklist-modal").style.display = "none";
        });


        document.getElementById('search-visitor').addEventListener('input', function() {
           const filter = this.value.toLowerCase();
              const rows = document.querySelectorAll('table tbody tr');
              rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
              });
        });

        

    </script>
</body>
</html>
