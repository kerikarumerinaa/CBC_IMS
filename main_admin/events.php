<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
                    <h2>Upcoming Events</h2>
                    <!-- Button to trigger modal -->
                    <button id="openModal" class="btn">Add New Event</button>
                </div>
                <!-- List of upcoming events -->
                <div id="eventsList">
                    <?php
                    // Read events from a file and display them
                    $eventsFile = 'events.txt';
                    if (file_exists($eventsFile)) {
                        $events = file($eventsFile, FILE_IGNORE_NEW_LINES);
                        foreach ($events as $event) {
                            list($name, $date) = explode('|', $event);
                            echo "<div class='event-item'><h3>$name</h3><p>$date</p></div>";
                        }
                    }
                    ?>
                </div>
                <!-- Modal -->
                <div id="eventModal" class="modal">
                    <div class="modal-content">
                        <span id="closeModal" class="close">&times;</span>
                        <h2>Add New Event</h2>
                        <form id="eventForm" method="POST" action="">
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

            <div class="card birthday-celebrants">
                <h2>Birthday Celebrants</h2>
                <!-- Content for birthday celebrants -->
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get modal elements
            var modal = document.getElementById("eventModal");
            var openModalBtn = document.getElementById("openModal");
            var closeModalBtn = document.getElementById("closeModal");

            // Open modal
            openModalBtn.onclick = function() {
                modal.style.display = "block";
            }

            // Close modal
            closeModalBtn.onclick = function() {
                modal.style.display = "none";
            }

            // Close modal when clicking outside of it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];

    // Save event to a file
    $eventsFile = 'events.txt';
    $eventEntry = $eventName . '|' . $eventDate . PHP_EOL;
    file_put_contents($eventsFile, $eventEntry, FILE_APPEND);

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
