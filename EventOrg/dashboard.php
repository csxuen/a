<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEvents | Events</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>

<body class="Dashboard">
<!-- zayzay part -->

<?php 
include("config.php");
include("header.php"); 
?>

<?php
$filter = $_GET['filter'] ?? 'all';

$totalEventsResult = $conn->query("SELECT COUNT(*) AS total FROM events");
$totalEvents = $totalEventsResult->fetch_assoc()['total'];

$pendingResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Pending'");
$pendingCount = $pendingResult->fetch_assoc()['total'];

$approvedResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Approved'");
$approvedCount = $approvedResult->fetch_assoc()['total'];

$rejectedResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Rejected'");
$rejectedCount = $rejectedResult->fetch_assoc()['total'];

$participantsResult = $conn->query("SELECT SUM(max_participants) AS total FROM events");
$participantsData = $participantsResult->fetch_assoc();
$totalParticipants = $participantsData['total'] ?? 0;
?>

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
    $sql = "SELECT * FROM events WHERE status = 'Pending' ORDER BY created_at DESC";
} elseif ($filter == 'approved') {
    $sql = "SELECT * FROM events WHERE status = 'Approved' ORDER BY created_at DESC";
} elseif ($filter == 'rejected') {
    $sql = "SELECT * FROM events WHERE status = 'Rejected' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM events ORDER BY created_at DESC";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
?>

<div class="event-card">
  <div class="event-image"
       style="<?php
       if (!empty($row['event_image'])) {
           echo "background-image:url('upload Event/".$row['event_image']."');
                 background-size:cover;
                 background-position:center;";
       } else {
           echo "background: var(--button);";
       }
       ?>">

    <div class="badge"><?php echo $row['event_type']; ?></div>

    <div class="status <?php echo strtolower($row['status']); ?>">
      <?php echo $row['status']; ?>
    </div>
  </div>

  <div class="event-content">
    <h3><?php echo $row['event_name']; ?></h3>
    <p><?php echo $row['description']; ?></p>
    <div class="event-info">📅 <?php echo $row['event_date']; ?></div>
    <div class="event-info">📍 <?php echo $row['event_location']; ?></div>

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
?>

</div>

<?php include("footer.php"); ?>

<script>
const imageInput = document.getElementById("eventImage");
const preview = document.getElementById("imagePreview");

if(imageInput){
  imageInput.addEventListener("change", function(){
    const file = this.files[0];
    if(file){
      const reader = new FileReader();
      reader.onload = function(e){
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });
}
</script>

</body>
</html>