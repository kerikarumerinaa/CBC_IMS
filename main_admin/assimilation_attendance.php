<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visitor Attendance</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="assimilation_attendance.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">

    <!-- Include the sidebar -->
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/db_connection.php'; ?>

    <!-- Main Content -->
    <main>
      <div class="attendance-tracker">
        <div class="header">
          <h2>Date: <?php echo date('F j, l'); ?></h2>
          <div class="top-right-container">
            <div class="total">TOTAL: <span id="total-count">0</span></div>
          </div>
        </div>

        <div class="attendee-lists">
          <!-- Active Visitors List -->
          <div class="attendee-list active-visitors">
            <div class="list-header">
              <span class="list-title">Active Visitors <span class="count" id="active-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar" id="search-active">
            </div>
            <div class="attendee-items" id="active-visitors">
              <!-- Active visitors fetched from the database will be displayed here -->
              <?php
                $query = "SELECT * FROM visitors";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<div class='attendee-item' data-id='{$row['id']}'>";
                    echo "<span>{$row['full_name']}</span>";
                    echo "<input type='checkbox' class='attendee-checkbox' data-name='{$row['full_name']}'>";
                    echo "</div>";
                  }
                } else {
                  echo "<div>No active visitors found</div>";
                }
              ?>
            </div>
          </div>

          <!-- Current Visitors List -->
          <div class="attendee-list current-visitors">
            <div class="list-header">
              <span class="list-title">Current Visitors <span class="count" id="current-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar" id="search-current">
            </div>
            <div class="attendee-items" id="current-visitors"></div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
   $(document).ready(function() {
  // Update counts
  function updateCounts() {
    const activeCount = $('#active-visitors .attendee-item').length;
    const currentCount = $('#current-visitors .attendee-item').length;
    $('#active-count').text(`(${activeCount})`);
    $('#current-count').text(`(${currentCount})`);
    $('#total-count').text(currentCount);
  }

  // Move to Current Visitors
  $(document).on('change', '.attendee-checkbox', function() {
    if (this.checked) {
      const attendeeItem = $(this).closest('.attendee-item').clone();
      attendeeItem.find('.attendee-checkbox')
        .removeClass('attendee-checkbox')  // Remove the checkbox class from active visitors
        .addClass('current-checkbox')     // Add the new class for current visitors
        .prop('checked', false);          // Uncheck the checkbox in Current Visitors

      // Append the cloned item to the current visitors list
      $('#current-visitors').append(attendeeItem);
      $(this).closest('.attendee-item').remove(); // Remove from Active Visitors
      updateCounts();
    }
  });

  // Move back to Active Visitors when unchecking in Current Visitors
  $(document).on('change', '.current-checkbox', function() {
    if (!this.checked) {
      const attendeeItem = $(this).closest('.attendee-item').clone();
      attendeeItem.find('.current-checkbox')
        .removeClass('current-checkbox')  // Remove the current checkbox class
        .addClass('attendee-checkbox')    // Add the original checkbox class
        .prop('checked', false);          // Uncheck the checkbox in Active Visitors

      // Append the cloned item back to Active Visitors
      $('#active-visitors').append(attendeeItem);
      $(this).closest('.attendee-item').remove(); // Remove from Current Visitors
      updateCounts();
    }
  });

  // Search functionality for Active Visitors
  $("#search-active").on("keyup", function() {
    const value = $(this).val().toLowerCase();
    $("#active-visitors .attendee-item").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  // Search functionality for Current Visitors
  $("#search-current").on("keyup", function() {
    const value = $(this).val().toLowerCase();
    $("#current-visitors .attendee-item").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  updateCounts();
});
  </script>
</body>
</html>
