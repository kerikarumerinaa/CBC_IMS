<?php
include '../includes/db_connection.php';

// Fetch events for FullCalendar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fetchEvents'])) {
    $events = [];
    $sql = "SELECT id, event_name, event_date FROM events";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = [
                    'id' => $row['id'],
                    'title' => $row['event_name'],
                    'start' => $row['event_date'], // FullCalendar expects this format
                    'end' => $row['event_date'],
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

    $sql = "INSERT INTO events (event_name, event_date) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $eventName, $eventDate);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }
    exit();
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
        <?php include '../includes/sidebar.php'; ?>

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
                <div id="eventModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span id="closeModal" class="close">&times;</span>
                        <h2>Add New Event</h2>
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
                                <button type="submit" class="btn">Save Event</button>
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
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize FullCalendar
            $('#calendar').fullCalendar({
                defaultView: 'month', // Default view is month
                events: 'events.php?fetchEvents=true', // Fetch events from this URL
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
        });
    </script>
</body>
</html>
