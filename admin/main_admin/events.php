<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../../includes/db_connection.php';

// Fetch events for FullCalendar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fetchEvents'])) {
    $events = [];
    $sql = "SELECT id, event_name, event_date, start_time, end_time, location, contact_person, other_details FROM events";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = [
                    'id' => $row['id'],
                    'title' => $row['event_name'],
                    'start' => $row['event_date'], // FullCalendar expects this format
                    'end' => $row['event_date'],
                    ''
                ];
            }
        }
        echo json_encode($events);
    } else {
        echo json_encode(['error' => 'Failed to fetch events: ' . $conn->error]);
    }
    exit();
}

// Add new event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $location = $_POST['location'];
    $contactPerson = $_POST['contactPerson'];
    $otherDetails = $_POST['otherDetails'];
    

    $sql = "INSERT INTO events (event_name, event_date, start_time, end_time, location, contact_person, other_details) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $eventName, $eventDate, $startTime, $endTime, $location, $contactPerson, $otherDetails);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }
    exit();
}


// // View a specific event
// if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['viewEvent'])) {
//     $eventId = $_GET['id'];
//     $sql = "SELECT * FROM events WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $eventId);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         echo json_encode($result->fetch_assoc());
//     } else {
//         echo json_encode(['error' => 'Event not found']);
//     }
//     exit();
// }



// // Edit an event
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEvent'])) {
//     $id = $_POST['id'];
//     $eventName = $_POST['eventName'];
//     $eventDate = $_POST['eventDate'];
//     $startTime = $_POST['startTime'];
//     $endTime = $_POST['endTime'];
//     $location = $_POST['location'];
//     $contactPerson = $_POST['contactPerson'];
//     $otherDetails = $_POST['otherDetails'];

//     $sql = "UPDATE events SET event_name = ?, event_date = ?, start_time = ?, end_time = ?, 
//             location = ?, contact_person = ?, other_details = ? WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("sssssssi", $eventName, $eventDate, $startTime, $endTime, $location, $contactPerson, $otherDetails, $id);

//     if ($stmt->execute()) {
//         echo "success";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
//     exit();
// }


// // Delete an event
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteEvent'])) {
//     $id = $_POST['id'];

//     $sql = "DELETE FROM events WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id);

//     if ($stmt->execute()) {
//         echo "Event deleted successfully.";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
//     exit();
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="events.css">
</head>
<body>
    <div class="container">
        <!-- Include the sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main>
            <h1>Events</h1>
            <div class="card upcoming-events">
                <div class="header">
                    <h2>Calendar</h2>
                    <!-- Button to trigger modal -->
                    <button id="openModal" class="btn">Add New Event</button>
                </div>
                <!-- FullCalendar Container -->
                <div id="calendar"></div>

                <!-- Modal for adding event -->
                <div id="eventModal" class="modal">
                    <div class="modal-content">
                        <span id="closeModal" class="close">&times;</span>
                        <h2>Event Details</h2>
                        <form id="eventForm">
                            <div class="form-group">
                                <label for="eventName">Event Name</label>
                                <input type="text" id="eventName" name="eventName" placeholder="Enter event name" required>
                            </div>
                            <div class="form-group">
                                <label for="eventDate">Event Date</label>
                                <input type="date" id="eventDate" name="eventDate" required>
                            </div>
                            <div class="form-group">
                                <label for="startTime">Start Time</label>
                                <input type="time" id="startTime" name="startTime" required>
                            </div>
                            <div class="form-group">
                                <label for="endTime">End Time</label>
                                <input type="time" id="endTime" name="endTime" required>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" placeholder="Enter location" required>
                            </div>
                            <div class="form-group">
                                <label for="contactPerson">Contact Person</label>
                                <input type="text" id="contactPerson" name="contactPerson" placeholder="Enter contact person" required>
                            </div>
                            <div class="form-group">
                                <label for="otherDetails">Other Details</label>
                                <textarea id="otherDetails" name="otherDetails" rows="3" placeholder="Enter additional details"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div id="viewModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeViewModal" class="close">&times;</span>
        <h2>View Event</h2>
        <form id="eventForm">
            <input type="hidden" id="eventId" />
            <div>
                <label for="eventName">Name:</label>
                <input type="text" id="eventName" />
            </div>
            <div>
                <label for="eventDate">Date:</label>
                <input type="date" id="eventDate" />
            </div>
            <div>
                <label for="startTime">Start Time:</label>
                <input type="time" id="startTime" />
            </div>
            <div>
                <label for="endTime">End Time:</label>
                <input type="time" id="endTime" />
            </div>
            <div>
                <label for="location">Location:</label>
                <input type="text" id="location" />
            </div>
            <div>
                <label for="contactPerson">Contact Person:</label>
                <input type="text" id="contactPerson" />
            </div>
            <div>
                <label for="otherDetails">Other Details:</label>
                <textarea id="otherDetails"></textarea>
            </div>
        </form>
        <div class="modal-actions">
            <button id="editButton" class="btn">Edit</button>
            <button id="deleteButton" class="btn">Delete</button>
            <button id="closeModalButton" class="btn">Close</button>
        </div>
    </div>
</div>
                

                <div id="editModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span id="closeEditModal" class="close">&times;</span>
                        <h2>Edit Event</h2>
                        <form id="editEventForm">
                            <input type="hidden" name="editEvent" value="true">
                            <input type="hidden" name="id" id="editEventId">
                            <div class="form-group">
                                <label for="editEventName">Event Name</label>
                                <input type="text" id="editEventName" name="eventName" required>
                            </div>
                            <div class="form-group">
                                <label for="editEventDate">Event Date</label>
                                <input type="date" id="editEventDate" name="eventDate" required>
                            </div>
                            <div class="form-group">
                                <label for="editStartTime">Start Time</label>
                                <input type="time" id="editStartTime" name="startTime" required>
                            </div>
                            <div class="form-group">
                                <label for="editEndTime">End Time</label>
                                <input type="time" id="editEndTime" name="endTime" required>
                            </div>
                            <div class="form-group">
                                <label for="editLocation">Location</label>
                                <input type="text" id="editLocation" name="location" required>
                            </div>
                            <div class="form-group">
                                <label for="editContactPerson">Contact Person</label>
                                <input type="text" id="editContactPerson" name="contactPerson" required>
                            </div>
                            <div class="form-group">
                                <label for="editOtherDetails">Other Details</label>
                                <textarea id="editOtherDetails" name="otherDetails" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn">Save Changes</button>
                        </form>
                        <button class="btn-close" onclick="closeModal('editModal')">Close</button>
                    </div>
                </div>



            </div>
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>

    <script>
       document.addEventListener("DOMContentLoaded", function () {
    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        defaultView: 'month', // Default view is month
        events: 'events.php?fetchEvents=true', // Fetch events from this URL
        eventClick: function(info) {
            const eventId = info.event.id; // Make sure this ID is set correctly
            fetch(`events.php?viewEvent=true&id=${eventId}`) // Check the correct URL and parameter
                .then(response => response.json())
                .then(event => {
                    if (event.error) {
                        alert(event.error); // In case of error
                    } else {
                        // Populate the modal with event details
                        document.getElementById('e').innerText = event.event_name;
                        document.getElementById('viewEventDate').innerText = event.event_date;
                        document.getElementById('viewStartTime').innerText = event.start_time;
                        document.getElementById('viewEndTime').innerText = event.end_time;
                        document.getElementById('viewLocation').innerText = event.location;
                        document.getElementById('viewContactPerson').innerText = event.contact_person;
                        document.getElementById('viewOtherDetails').innerText = event.other_details;

                        // Show the modal
                        document.getElementById('viewModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
});


            // Add event listener for form submission
            document.getElementById('eventForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('events.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log('Response:', data);
                        if (data.trim() === 'success') {
                            
                        // Refresh the calendar to show the new event
                        $('#calendar').fullCalendar('refetchEvents');
                        // Close the modal
                        document.getElementById('eventModal').style.display = 'none';
                        } else {
                            alert('Error adding event: ' + data);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Open modal
            document.getElementById('openModal').addEventListener('click', function () {
                document.getElementById('eventModal').style.display = 'block';
            });

            // Close modal
            document.getElementById('closeModal').addEventListener('click', function () {
                document.getElementById('eventModal').style.display = 'none';
            });


        // CRUD operations
        document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-event').forEach(button => {
        button.addEventListener('click', function () {
            const eventId = this.dataset.id;
            fetch(`events.php?viewEvent=true&id=${eventId}`)
                .then(response => response.json())
                .then(event => {
                    if (event.error) {
                        alert(event.error);
                    } else {
                        document.getElementById('viewEventName').innerText = event.event_name;
                        document.getElementById('viewEventDate').innerText = event.event_date;
                        document.getElementById('viewStartTime').innerText = event.start_time;
                        document.getElementById('viewEndTime').innerText = event.end_time;
                        document.getElementById('viewLocation').innerText = event.location;
                        document.getElementById('viewContactPerson').innerText = event.contact_person;
                        document.getElementById('viewOtherDetails').innerText = event.other_details;
                        document.getElementById('viewModal').style.display = 'block';
                    }
                });
        });
    });

    // Similarly add for Edit and Delete buttons
    attachEditListeners();
    attachDeleteListeners();
});


function openEditModal(event) {
     // Populate edit form with the event data
     document.getElementById('editEventId').value = event.id;
     document.getElementById('editEventName').value = event.event_name;
     document.getElementById('editEventDate').value = event.event_date;
     document.getElementById('editStartTime').value = event.start_time;
     document.getElementById('editEndTime').value = event.end_time;
     document.getElementById('editLocation').value = event.location;
     document.getElementById('editContactPerson').value = event.contact_person;
     document.getElementById('editOtherDetails').value = event.other_details;

     // Show the edit modal
     document.getElementById('viewModal').style.display = 'none';
     document.getElementById('editModal').style.display = 'block';

     document.getElementById('editEventForm').onsubmit = function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/events.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Reload the calendar to reflect changes
    })
    .catch(error => console.error('Error:', error));
};

}



function deleteEvent(eventId) {
     if (confirm('Are you sure you want to delete this event?')) {
         fetch(`/events.php`, {
             method: 'POST',
             body: new URLSearchParams({
                 deleteEvent: true,
                 id: eventId,
             })
         })
         .then(response => response.text())
         .then(data => {
             alert(data);
             location.reload(); // Reload the calendar to reflect changes
         });
     }
}
    </script>
</body>
</html>
