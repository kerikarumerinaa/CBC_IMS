<?php
include '../includes/db_connection.php'; 

// Handle form submission to add an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventName'])) {
    $eventName = $conn->real_escape_string($_POST['eventName']);
    $eventDate = $_POST['eventDate'];

    $sql = "INSERT INTO events (event_name, event_date) VALUES ('$eventName', '$eventDate')";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
    exit();
}

// Fetch events for the calendar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fetchEvents'])) {
    $events = [];
    $sql = "SELECT id, event_name, event_date FROM events";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = [
                'id' => $row['id'],
                'title' => $row['event_name'],
                'start' => $row['event_date'],
                'end' => $row['event_date'],
                'category' => 'allday',
            ];
        }
    }

    // Output events as JavaScript array format
    echo "var events = " . json_encode($events) . ";";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css">
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script>
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
                <!-- TUI Calendar Container -->
                <div id="calendar" style="height: 700px;"></div>
                <button id="prevMonth">Previous Month</button>
                <button id="nextMonth">Next Month</button>

                <!------------------------------ Modal --------------------------------->
                <div id="eventModal" class="modal">
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

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize TUI Calendar
        var calendar = new tui.Calendar('#calendar', {
            defaultView: 'month',
            taskView: false,
            scheduleView: true,
            useCreationPopup: false,
            useDetailPopup: true,
        });

        // Fetch events from the server
        function loadEvents() {
            const script = document.createElement('script');
            script.src = 'events.php?fetchEvents=true';
            script.onload = () => {
                calendar.clear();
                calendar.createSchedules(events); // `events` is populated by the PHP script
            };
            document.body.appendChild(script);
        }

        // Add event listener for adding new events
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
                        alert('Event added successfully');
                        loadEvents(); // Reload events
                        document.getElementById('eventModal').style.display = 'none'; // Close modal
                    } else {
                        alert('Error adding event: ' + data);
                    }
                });
        });

        // Navigation buttons
        document.getElementById('prevMonth').addEventListener('click', function () {
            calendar.prev();
        });

        document.getElementById('nextMonth').addEventListener('click', function () {
            calendar.next();
        });

        // Modal controls
        const modal = document.getElementById('eventModal');
        document.getElementById('openModal').addEventListener('click', () => {
            modal.style.display = 'block';
        });
        document.getElementById('closeModal').addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Load events on page load
        loadEvents();
    });
</script>

</body>
</html>
