<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'main_admin')) {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db_connection.php';

// Fetch events for FullCalendar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fetchEvents'])) {
    fetchEvents($conn);
    exit();
}

// Add new event
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['editEvent']) && !isset($_POST['deleteEvent'])) {
    addEvent($conn);
    exit();
}

// View a specific event
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['viewEvent'])) {
    viewEvent($conn);   
    exit();
}

// Edit an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEvent'])) {
    editEvent($conn);
    exit();
}

// Delete an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteEvent'])) {
    deleteEvent($conn);
    exit();
}

function fetchEvents($conn) {
    $events = [];
    $sql = "SELECT id, event_name, event_date, start_time, end_time, location, contact_person, other_details FROM events";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $events[] = [
                'id' => $row['id'],
                'title' => $row['event_name'],
                'start' => $row['event_date'],
                'end' => $row['event_date'],
            ];
        }
        echo json_encode($events);
    } else {
        echo json_encode(['error' => 'Failed to fetch events: ' . $conn->error]);
    }
}

function addEvent($conn) {
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
}

function viewEvent($conn) {
    $eventId = $_GET['id'];
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Event not found']);
    }
}

function editEvent($conn) {
    $id = $_POST['id'];
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $location = $_POST['location'];
    $contactPerson = $_POST['contactPerson'];
    $otherDetails = $_POST['otherDetails'];

    $sql = "UPDATE events SET event_name = ?, event_date = ?, start_time = ?, end_time = ?, 
            location = ?, contact_person = ?, other_details = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $eventName, $eventDate, $startTime, $endTime, $location, $contactPerson, $otherDetails, $id);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        die();
    } else {
        echo "success";
    }
}

function deleteEvent($conn) {
    $id = $_POST['id'];

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Event deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
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
                     <div class="buttons">
                    <button id="refreshCalendar" class="refresh-btn" onclick="window.location.href = 'events.php'">Refresh</button>
                    <button id="openModal" class="btn">Add New Event</button>
                    </div>
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
                                <button type="submit" class="btn">Save Event</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div id="viewModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span id="closeViewModal" class="close">&times;</span>
                    <h2>View Event</h2>
                    <form>
                        <div class="form-group">
                            <label for="eventName">Event Name</label>
                            <input type="text" id="viewEventName" readonly>
                        </div>
                        <div class="form-group">
                            <label for="eventDate">Event Date</label>
                            <input type="date" id="viewEventDate" readonly>
                        </div>
                        <div class="form-group">
                            <label for="startTime">Start Time</label>
                            <input type="time" id="viewStartTime" readonly>
                        </div>
                        <div class="form-group">
                            <label for="endTime">End Time</label>
                            <input type="time" id="viewEndTime" readonly>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="viewLocation" readonly>
                        </div>
                        <div class="form-group">
                            <label for="contactPerson">Contact Person</label>
                            <input type="text" id="viewContactPerson" readonly>
                        </div>
                        <div class="form-group">
                            <label for="otherDetails">Other Details</label>
                            <textarea id="viewOtherDetails" readonly></textarea>
                        </div>
                    </form>
                    <div class="modal-actions">
                        <button id="editButton" class="btn">Edit</button>
                        <button id="deleteButton" class="btn delete">Delete</button>
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
                            <div class="form-group">
                                <button type="submit" class="btn">Save Changes</button>
                            </div>
                        </form>
                        
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
//        document.addEventListener("DOMContentLoaded", function () {
//     // Initialize FullCalendar
//     $('#calendar').fullCalendar({
//         defaultView: 'month', // Default view is month
//         events: 'events.php?fetchEvents=true', // Fetch events from this URL
//         eventClick: function (event) {
//             // Open the view modal on event click
//             fetch(`events.php?viewEvent=true&id=${event.id}`)
//                 .then(response => response.json())
//                 .then(event => {
//                     if (event.error) {
//                         alert(event.error); // Handle errors
//                     } else {
//                         // Populate the modal with event details
//                         document.getElementById('viewEventName').innerText = event.event_name;
//                         document.getElementById('viewEventDate').innerText = event.event_date;
//                         document.getElementById('viewStartTime').innerText = event.start_time;
//                         document.getElementById('viewEndTime').innerText = event.end_time;
//                         document.getElementById('viewLocation').innerText = event.location;
//                         document.getElementById('viewContactPerson').innerText = event.contact_person;
//                         document.getElementById('viewOtherDetails').innerText = event.other_details;

//                         // Show the modal
//                         document.getElementById('viewModal').style.display = 'block';

//                         // Attach event handlers for edit and delete
//                         document.getElementById('editButton').onclick = function () {
//                             openEditModal(event);
//                         };
//                         document.getElementById('deleteButton').onclick = function () {
//                             deleteEvent(event.id);
//                         };
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error:', error);
//                 });
//         }
//     });

//     // Add event listener for form submission to create new event
//     document.getElementById('eventForm').addEventListener('submit', function (e) {
//         e.preventDefault();
//         const formData = new FormData(this);

//         fetch('events.php', {
//             method: 'POST',
//             body: formData,
//         })
//             .then(response => response.text())
//             .then(data => {
//                 if (data.trim() === 'success') {
//                     $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
//                     document.getElementById('eventModal').style.display = 'none'; // Close modal
//                 } else {
//                     alert('Error adding event: ' + data);
//                 }
//             })
//             .catch(error => console.error('Error:', error));
//     });

//     // Open modal for adding new event
//     document.getElementById('openModal').addEventListener('click', function () {
//         document.getElementById('eventModal').style.display = 'block';
//     });

//     // Close modal for adding event
//     document.getElementById('closeModal').addEventListener('click', function () {
//         document.getElementById('eventModal').style.display = 'none';
//     });

//     // Close view modal
//     document.getElementById('closeViewModal').addEventListener('click', function () {
//         document.getElementById('viewModal').style.display = 'none';
//     });

//     // Function to open the edit modal with prefilled data
//     function openEditModal(event) {
//         document.getElementById('editEventId').value = event.id;
//         document.getElementById('editEventName').value = event.event_name;
//         document.getElementById('editEventDate').value = event.event_date;
//         document.getElementById('editStartTime').value = event.start_time;
//         document.getElementById('editEndTime').value = event.end_time;
//         document.getElementById('editLocation').value = event.location;
//         document.getElementById('editContactPerson').value = event.contact_person;
//         document.getElementById('editOtherDetails').value = event.other_details;

//         // Show the edit modal
//         document.getElementById('editModal').style.display = 'block';
//     }

//     // Close edit modal
//     document.getElementById('closeEditModal').addEventListener('click', function () {
//         document.getElementById('editModal').style.display = 'none';
//     });

//     // Handle edit form submission
//     document.getElementById('editEventForm').onsubmit = function (e) {
//         e.preventDefault();
//         const formData = new FormData(this);

//         fetch('events.php', {
//             method: 'POST',
//             body: formData,
//         })
//             .then(response => response.text())
//             .then(data => {
//                 alert(data);
//                 $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
//                 document.getElementById('editModal').style.display = 'none'; // Close modal
//             })
//             .catch(error => console.error('Error:', error));
//     };

//     // Function to delete an event
//     function deleteEvent(eventId) {
//         if (confirm('Are you sure you want to delete this event?')) {
//             fetch('events.php', {
//                 method: 'POST',
//                 body: new URLSearchParams({ deleteEvent: true, id: eventId }),
//             })
//                 .then(response => response.text())
//                 .then(data => {
//                     alert(data);
//                     $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
//                 })
//                 .catch(error => console.error('Error:', error));
//         }
//     }
// });

document.addEventListener("DOMContentLoaded", function () {
    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        defaultView: 'month',
        events: 'events.php?fetchEvents=true', // Fetch events from the server
        eventClick: function (event) {
            // Fetch event details and open the view modal
            console.log(event)
            fetchEventDetails(event.id);
        },
    });

    // Handle new event form submission
    document.getElementById('eventForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('events.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                    closeModal('eventModal'); // Close the new event modal
                } else {
                    alert('Error adding event: ' + data);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    //for updateing the item in the view modal
    // document.getElementById('edit-submit').addEventListener('click', function (e) {
    //     //console.log("e.id");
    //     updateEventDetails(e.id);
    // });

    // Open modal for adding a new event
    document.getElementById('openModal').addEventListener('click', function () {
        openModal('eventModal');
    });

    // Close modals
    document.getElementById('closeModal').addEventListener('click', function () {
        closeModal('eventModal');
    });
    document.getElementById('closeViewModal').addEventListener('click', function () {
        closeModal('viewModal');
    });
    document.getElementById('closeEditModal').addEventListener('click', function () {
        closeModal('editModal');
    });

    // Handle edit form submission
    document.getElementById('editEventForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const eventId = document.getElementById('editEventId').value; // Get the event ID

        fetch(`events.php?editEvent=true&id=${eventId}`, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                    closeModal('editModal'); // Close edit modal
                } else {
                    alert('Error updating event: ' + data);
                }
                //alert(data);
                console.log(data);
                
                
            })
            .catch(error => console.error('Error:', error));
    });

    // Fetch event details for viewing
    function fetchEventDetails(eventId) {
        fetch(`events.php?viewEvent=true&id=${eventId}`)
            .then(response => response.json())
            .then(event => {
                if (event.error) {
                    alert(event.error);
                } else {
                    populateViewModal(event);
                    openModal('viewModal');

                    // Attach handlers for edit and delete buttons
                    document.getElementById('editButton').onclick = function () {
                        openEditModal(event);
                    };
                    document.getElementById('deleteButton').onclick = function () {
                        deleteEvent(event.id);
                    };
                }
            })
            .catch(error => console.error('Error fetching event details:', error));
    }

    //FOR UPDATING THE ITEM IN THE VIEW MODAL
    // function updateEventDetails(eventId) {
    //     fetch(`events.php?editEvent=true&id=${eventId}`)
    //     .then(response => response.json())
    //         .then(event => {
    //             if (event.error) {
    //                 alert(event.error);
    //             } else {
    //                 console.log("success");
    //                 //populateViewModal(event);
    //                 //openModal('viewModal');

    //                 // Attach handlers for edit and delete buttons
    //                 //document.getElementById('editButton').onclick = function () {
    //                    // openEditModal(event);
    //                 //};
                   
    //             }
    //         })
    //         .catch(error => console.error('Error update event details:', error));
    // }

    // Populate the view modal
    function populateViewModal(event) {
        document.getElementById('viewEventName').value = event.event_name;
        document.getElementById('viewEventDate').value = event.event_date;
        document.getElementById('viewStartTime').value = event.start_time;
        document.getElementById('viewEndTime').value = event.end_time;
        document.getElementById('viewLocation').value = event.location;
        document.getElementById('viewContactPerson').value = event.contact_person;
        document.getElementById('viewOtherDetails').innerText = event.other_details;
    }

    // Open the edit modal with pre-filled data
    function openEditModal(event) {
        document.getElementById('editEventId').value = event.id;
        document.getElementById('editEventName').value = event.event_name;
        document.getElementById('editEventDate').value = event.event_date;
        document.getElementById('editStartTime').value = event.start_time;
        document.getElementById('editEndTime').value = event.end_time;
        document.getElementById('editLocation').value = event.location;
        document.getElementById('editContactPerson').value = event.contact_person;
        document.getElementById('editOtherDetails').innerText = event.other_details;

        openModal('editModal');
        closeModal('viewModal'); 
    }

    // Delete an event
    function deleteEvent(eventId) {
        if (confirm('Are you sure you want to delete this event?')) {
            fetch('events.php', {
                method: 'POST',
                body: new URLSearchParams({ deleteEvent: true, id: eventId }),
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                    closeModal('viewModal'); // Close the view modal after deletion
                })
                .catch(error => console.error('Error deleting event:', error));
        }
    }

    // Helper functions
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
});

    </script>
</body>
</html>
