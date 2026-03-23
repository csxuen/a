<?php 
include("config.php");
include("header.php"); 

$filter = $_GET['filter'] ?? 'all';

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

/* counts for filter tabs */
$totalEventsResult = $conn->query("SELECT COUNT(*) AS total FROM events");
$totalEvents = $totalEventsResult->fetch_assoc()['total'];

$pendingResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Pending'");
$pendingCount = $pendingResult->fetch_assoc()['total'];

$approvedResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Approved'");
$approvedCount = $approvedResult->fetch_assoc()['total'];

$rejectedResult = $conn->query("SELECT COUNT(*) AS total FROM events WHERE status = 'Rejected'");
$rejectedCount = $rejectedResult->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Events | EcoEvents</title>
<link rel="stylesheet" href="CSS/style.css">
</head>

<body class="Dashboard">

<div class="EventDiscrption">
  <div class="EventDiscrption-text">
    <h1>All Events</h1>
    <p>Browse and filter event listings</p>
  </div>
</div>

<div class="tabs">
  <a href="event_page.php?filter=all" class="tab <?php echo ($filter == 'all') ? 'active' : ''; ?>">
    All Events (<?php echo $totalEvents; ?>)
  </a>

  <a href="event_page.php?filter=pending" class="tab <?php echo ($filter == 'pending') ? 'active' : ''; ?>">
    Pending (<?php echo $pendingCount; ?>)
  </a>

  <a href="event_page.php?filter=approved" class="tab <?php echo ($filter == 'approved') ? 'active' : ''; ?>">
    Approved (<?php echo $approvedCount; ?>)
  </a>

  <a href="event_page.php?filter=rejected" class="tab <?php echo ($filter == 'rejected') ? 'active' : ''; ?>">
    Rejected (<?php echo $rejectedCount; ?>)
  </a>
</div>

<div class="events">

<?php if ($result->num_rows > 0) { ?>
  <?php while($row = $result->fetch_assoc()) { ?>

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

  <?php } ?>
<?php } else { ?>
  <p>No events found for this status.</p>
<?php } ?>

</div>

<?php include("footer.php"); ?>

</body>
</html>