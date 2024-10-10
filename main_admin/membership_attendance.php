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
        <div class="attendee-list active-attenders">
          <div class="list-header">
            <span class="list-title">Active Attenders <span class="count" id="active-count">(0)</span></span>
            <input type="text" placeholder="Search" class="search-bar" id="search-active">
          </div>
          <div class="attendee-items" id="active-attenders">
            <?php
              // Fetch members from the database
              $query = "SELECT * FROM members WHERE status='active'";
              $result = $conn->query($query);

              // Check if there are results and display them
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

<script>
  $(document).ready(function() {
    function updateCounts() {
      const activeCount = $('#active-attenders .attendee-item').length;
      const currentCount = $('#current-attenders .attendee-item').length;
      $('#active-count').text(`(${activeCount})`);
      $('#current-count').text(`(${currentCount})`);
      $('#total-count').text(currentCount);
    }

    $('.attendee-checkbox').change(function() {
      if (this.checked) {
        const attendeeItem = $(this).closest('.attendee-item').clone();
        attendeeItem.find('.attendee-checkbox').remove();
        $('#current-attenders').append(attendeeItem);
        $(this).closest('.attendee-item').remove();
        updateCounts();
      } else {
        const attendeeName = $(this).data('name');
        $('#current-attenders .attendee-item').filter(function() {
          return $(this).text().trim() === attendeeName;
        }).remove();
        $('#active-attenders').append($(this).closest('.attendee-item').clone());
        $(this).closest('.attendee-item').remove();
        updateCounts();
      }
    });

    $("#search-active").on("keyup", function() {
      const value = $(this).val().toLowerCase();
      $("#active-attenders .attendee-item").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    updateCounts();
  });
</script>
</body>
</html>
