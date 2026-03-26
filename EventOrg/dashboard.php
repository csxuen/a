<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$organizer_id = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';

/* Total Events */
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ?");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$totalEventsResult = $stmt->get_result();
$totalEvents = $totalEventsResult->fetch_assoc()['total'];
$stmt->close();

/* Pending Events */
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Pending'");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$pendingResult = $stmt->get_result();
$pendingCount = $pendingResult->fetch_assoc()['total'];
$stmt->close();

/* Approved Events */
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Approved'");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$approvedResult = $stmt->get_result();
$approvedCount = $approvedResult->fetch_assoc()['total'];
$stmt->close();

/* Rejected Events */
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Rejected'");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$rejectedResult = $stmt->get_result();
$rejectedCount = $rejectedResult->fetch_assoc()['total'];
$stmt->close();

/* Total Participants */
$stmt = $conn->prepare("SELECT SUM(max_participants) AS total FROM events WHERE organizer_id = ?");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$participantsResult = $stmt->get_result();
$participantsData = $participantsResult->fetch_assoc();
$totalParticipants = $participantsData['total'] ?? 0;
$stmt->close();

include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEvents | Events</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
  <div class="Dashboard">

    <div class="EventDiscrption">
      <div class="EventDiscrption-text">
        <h1>Organizer Dashboard</h1>
        <p>Manage your sustainable events</p>
      </div>

      <a href="#" id="openCreateModal" class="btn"><b>＋ Create Event</b></a>
      <?php include("CreateEvent.php"); ?>

      <script src="js/main.js"></script>

      <script>
        const modal = document.getElementById('createEventModal');
        const openBtn = document.getElementById('openCreateModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelBtn');
        const form = document.getElementById('createEventForm');

        openBtn.addEventListener('click', (e) => {
          e.preventDefault();
          modal.style.display = 'flex';
          document.body.classList.add('modal-open');
        });

        function closeModal() {
          modal.style.display = 'none';
          form.reset();
          document.body.classList.remove('modal-open');
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        window.addEventListener('click', (e) => {
          if (e.target === modal) {
            closeModal();
          }
        });
      </script>
    </div>

    <div class="stats">
        <div class="stat-card"><h3>My Events</h3><span><?php echo $totalEvents; ?></span></div>
        <div class="stat-card"><h3>Total Participants</h3><span><?php echo $totalParticipants; ?></span></div>
        <div class="stat-card"><h3>Pending Approval</h3><span><?php echo $pendingCount; ?></span></div>
        <div class="stat-card"><h3>Approved Events</h3><span><?php echo $approvedCount; ?></span></div>
    </div>

    <div class="tabs">
      <a href="dashboard.php?filter=all" class="tab <?php echo ($filter == 'all') ? 'active' : ''; ?>">
        All Events (<?php echo $totalEvents; ?>)
      </a>

      <a href="dashboard.php?filter=pending" class="tab <?php echo ($filter == 'pending') ? 'active' : ''; ?>">
        Pending (<?php echo $pendingCount; ?>)
      </a>

      <a href="dashboard.php?filter=approved" class="tab <?php echo ($filter == 'approved') ? 'active' : ''; ?>">
        Approved (<?php echo $approvedCount; ?>)
      </a>

      <a href="dashboard.php?filter=rejected" class="tab <?php echo ($filter == 'rejected') ? 'active' : ''; ?>">
        Rejected (<?php echo $rejectedCount; ?>)
      </a>
    </div>

    <div class="events">

    <?php
    if ($filter == 'pending') {
        $sql = "SELECT * FROM events WHERE organizer_id = ? AND status = 'Pending' ORDER BY created_at DESC";
    } elseif ($filter == 'approved') {
        $sql = "SELECT * FROM events WHERE organizer_id = ? AND status = 'Approved' ORDER BY created_at DESC";
    } elseif ($filter == 'rejected') {
        $sql = "SELECT * FROM events WHERE organizer_id = ? AND status = 'Rejected' ORDER BY created_at DESC";
    } else {
        $sql = "SELECT * FROM events WHERE organizer_id = ? ORDER BY created_at DESC";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $organizer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
    ?>

    <div class="event-card">
      <div class="event-image"
          style="<?php
          if (!empty($row['event_image'])) {
              echo "background-image:url('upload Event/" . htmlspecialchars($row['event_image']) . "');
                    background-size:cover;
                    background-position:center;";
          } else {
              echo "background: var(--button);";
          }
          ?>">

        <div class="badge"><?php echo htmlspecialchars($row['event_type']); ?></div>

        <div class="status <?php echo strtolower(htmlspecialchars($row['status'])); ?>">
          <?php echo htmlspecialchars($row['status']); ?>
        </div>
      </div>

      <div class="event-content">
        <h3><?php echo htmlspecialchars($row['event_name']); ?></h3>
        <p><?php echo htmlspecialchars($row['description']); ?></p>
        <div class="event-info">📅 <?php echo htmlspecialchars($row['event_date']); ?></div>
        <div class="event-info">📍 <?php echo htmlspecialchars($row['event_location']); ?></div>

        <div class="event-footer">
          <span class="rating">⭐ 4.8</span>
          <a href="event_details.php?id=<?php echo $row['id']; ?>" class="view-btn">View Details</a>
        </div>
      </div>
    </div>

    <?php
        }
    } else {
        echo "<p>No events found for this status.</p>";
    }

    $stmt->close();
    ?>

    </div>


    <script>
      const imageInput = document.getElementById("eventImage");
      const preview = document.getElementById("imagePreview");

      if (imageInput) {
        imageInput.addEventListener("change", function() {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              preview.src = e.target.result;
              preview.style.display = "block";
            };
            reader.readAsDataURL(file);
          }
        });
      }
    </script>
  </div>
  <!-- footer.php -->
  <footer class="footer">
    <div class="footer-grid">
      <div>
        <h4>Namewebsite</h4>
        <p>Applying environmental action and community events to create a sustainable future.</p>
      </div>
      <div>
        <h4>Quick Links</h4>
        <a href="events.php">Browse Events</a>
        <a href="#">Become an Organizer</a>
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
      </div>
      <div>
        <h4>Contact</h4>
        <p>ecohotline@susevent.com</p>
        <p>+60123456789</p>
        <p>Bukit Jalil, Kuala Lumpur</p>
        <p>18001231234</p>
      </div>
    </div>
    <div class="copyright">
      &copy; 2026 EcoEvents. All rights reserved.
    </div>
  </footer>

</body>
</html>