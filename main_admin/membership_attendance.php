<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="membership_attendance.css">
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
            <button class="add-visitor-button">Add Visitor</button>
          </div>
        </div>

        <div class="attendee-lists">
          <!-- Active Attenders List -->
          <div class="attendee-list active-attenders">
            <div class="list-header">
              <span class="list-title">Active Attenders <span class="count" id="active-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar" id="search-active">
            </div>
            <div class="attendee-items" id="active-attenders">
              <!-- Active attenders fetched from the database will be displayed here -->
              <?php
                $query = "SELECT * FROM members WHERE status='active'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<div class='attendee-item'>";
                    echo "<span>{$row['full_name']}</span>";
                    echo "<input type='checkbox' class='attendee-checkbox' data-name='{$row['full_name']}'>";
                    echo "</div>";
                  }
                } else {
                  echo "<div>No active attenders found</div>";
                }
              ?>
            </div>
          </div>

          <!-- Current Attenders List -->
          <div class="attendee-list current-attenders">
            <div class="list-header">
              <span class="list-title">Current Attenders <span class="count" id="current-count">(0)</span></span>
              <input type="text" placeholder="Search" class="search-bar">
            </div>
            <div class="attendee-items" id="current-attenders"></div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal for adding a visitor -->
  <div id="addVisitorModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Add Visitor</h2>
      <form id="addVisitorForm">
        <label for="visit-date">Date</label>
        <input type="text" id="visit-date" name="visit_date" value="<?php echo date('Y-m-d'); ?>" readonly>

        <label for="visitor-name">Name</label>
        <input type="text" id="visitor-name" name="visitor_name" required>

        <label for="visitor-address">Address</label>
        <input type="text" id="visitor-address" name="visitor_address" required>

        <label for="visitor-civil-status">Civil Status</label>
        <select id="visitor-civil-status" name="civil_status">
          <option value="single">Single</option>
          <option value="married">Married</option>
          <option value="divorced">Divorced</option>
          <option value="widowed">Widowed</option>
        </select>

        <label for="visitor-phone">Phone Number</label>
        <input type="text" id="visitor-phone" name="phone_number" required>

        <label for="visitor-age">Age</label>
        <input type="number" id="visitor-age" name="age" required>

        <label for="visitor-email">Email Address</label>
        <input type="email" id="visitor-email" name="email_address">

        <label for="invited-by">Invited by</label>
        <input type="text" id="invited-by" name="invited_by">

        <label for="interviewed-by">Interviewed/Counseled by</label>
        <input type="text" id="interviewed-by" name="interviewed_by">

        <input type="submit" value="Submit">
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      function updateCounts() {
        const activeCount = $('#active-attenders .attendee-item').length;
        const currentCount = $('#current-attenders .attendee-item').length;
        $('#active-count').text(`(${activeCount})`);
        $('#current-count').text(`(${currentCount})`);
        $('#total-count').text(currentCount);
      }

      // Modal functionality
      const modal = $('#addVisitorModal');
      const closeModal = $('.close');
      const openModalButton = $('.add-visitor-button');

      openModalButton.on('click', function() {
        modal.show();
      });

      closeModal.on('click', function() {
        modal.hide();
      });

      $(window).on('click', function(event) {
        if (event.target === modal[0]) {
          modal.hide();
        }
      });

      // Event delegation for dynamically created checkboxes
      $(document).on('change', '.attendee-checkbox', function() {
        if (this.checked) {
          const attendeeItem = $(this).closest('.attendee-item').clone();
          attendeeItem.find('.attendee-checkbox').remove();
          $('#current-attenders').append(attendeeItem);
          $(this).hide();
          updateCounts();
        }
      });

      // Search functionality for the "Active Attenders" list
      $("#search-active").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#active-attenders .attendee-item").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      updateCounts();
    });
  </script>
</body>
</html>
