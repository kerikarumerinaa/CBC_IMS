<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Attendance</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="membership_attendance.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">
    <!-- Include the sidebar -->
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <main>
      <div class="attendance-tracker">
        <div class="header">
          <h2>Date: <?php echo date('F j, l'); ?></h2>
          <div class="top-right-container">
            <div class="total">TOTAL: <span id="total-count">0</span></div>
          </div>
        </div>

        <div class="description">
          <label for="description">Description:</label>
          <input type="text"  id="description">
        </div>

        <button id="save-attendance-btn">Save Attendance</button>
        <form id="attendance-form" action="attendance_history.php" method="POST" style="display:none;">
            <input type="hidden" name="date" />
            <input type="hidden" name="description" />
            <input type="hidden" name="attendees" />
          </form>


        <div class="attendee-lists">
          <!-- Active Attenders List -->
          <div class="attendee-list">
            <div class="list-header">
              <span class="list-title">Active Attenders <span class="count" id="active-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar" id="search-active">
            </div>
            <div class="attendee-items" id="active-attenders">
              <!-- Members from the database will be displayed here -->
              <?php
                $query = "SELECT * FROM members"; // Fetch all members
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<div class='attendee-item' data-id='{$row['id']}'>";
                    echo "<span>{$row['full_name']}</span>";
                    echo "<input type='checkbox' class='attendee-checkbox' data-name='{$row['full_name']}'>";
                    echo "</div>";
                  }
                } else {
                  echo "<div>No members found</div>";
                }
              ?>
            </div>
          </div>

          <!-- Current Attenders List -->
          <div class="attendee-list">
            <div class="list-header">
              <span class="list-title">Current Attenders <span class="count" id="current-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar" id="search-current">
            </div>
            <div class="attendee-items" id="current-attenders">
              <!-- Checked members will be moved here -->
            </div>
          </div>
        </div>
      </div>
    </main>                    
  </div>
  


  <script>
    $(document).ready(function() {
      // Update counts
      function updateCounts() {
        const activeCount = $('#active-attenders .attendee-item').length;
        const currentCount = $('#current-attenders .attendee-item').length;
        $('#active-count').text(`(${activeCount})`);
        $('#current-count').text(`(${currentCount})`);
        $('#total-count').text(currentCount);
      }

      // Move to Current Attenders
      $(document).on('change', '.attendee-checkbox', function() {
        if (this.checked) {
          const attendeeItem = $(this).closest('.attendee-item').clone();
          attendeeItem.find('.attendee-checkbox')
            .removeClass('attendee-checkbox')
            .addClass('current-checkbox')
            .prop('checked', false); // Uncheck the checkbox in Current Attenders
          
            $('#current-attenders').append(attendeeItem);
          $(this).closest('.attendee-item').remove(); // Remove from Active Attenders
          updateCounts();
        }
      });

      // Move back to Active Attenders
      $(document).on('change', '.current-checkbox', function() {
        if (!this.checked) {
          const attendeeItem = $(this).closest('.attendee-item').clone();
          attendeeItem.find('.current-checkbox')
            .removeClass('current-checkbox')
            .addClass('attendee-checkbox')
            .prop('checked', false); // Uncheck the checkbox in Active Attenders
          
            $('#active-attenders').append(attendeeItem);
          $(this).closest('.attendee-item').remove(); // Remove from Current Attenders
          updateCounts();
        }
      });

      // Search functionality for Active Attenders
      $("#search-active").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#active-attenders .attendee-item").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      // Search functionality for Current Attenders
      $("#search-current").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#current-attenders .attendee-item").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      updateCounts();
    });



    $(document).ready(function () {
  $("#save-attendance-btn").on("click", function () {
    const attendees = [];
    $("#current-attenders .attendee-item").each(function () {
      attendees.push($(this).data("id")); // Collect attendee IDs
    });

    const description = $("#description").val().trim();
    if (!description) {
      alert("Please enter a description for the event.");
      return;
    }

    if (attendees.length === 0) {
      alert("No attendees to save.");
      return;
    }

    const date = new Date().toISOString().split("T")[0]; // Format: YYYY-MM-DD

    // Set the form values to be submitted
    $("#attendance-form input[name='date']").val(date);
    $("#attendance-form input[name='description']").val(description);
    $("#attendance-form input[name='attendees']").val(attendees.join(",")); // Join IDs with commas

    // Submit the form
    $("#attendance-form").submit();
  });
});


  </script>
</body>
</html>
