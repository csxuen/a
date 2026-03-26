<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$organizer_id = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';

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

/* counts for filter tabs */
$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ?");
$stmt2->bind_param("i", $organizer_id);
$stmt2->execute();
$totalEventsResult = $stmt2->get_result();
$totalEvents = $totalEventsResult->fetch_assoc()['total'];
$stmt2->close();

$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Pending'");
$stmt2->bind_param("i", $organizer_id);
$stmt2->execute();
$pendingResult = $stmt2->get_result();
$pendingCount = $pendingResult->fetch_assoc()['total'];
$stmt2->close();

$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Approved'");
$stmt2->bind_param("i", $organizer_id);
$stmt2->execute();
$approvedResult = $stmt2->get_result();
$approvedCount = $approvedResult->fetch_assoc()['total'];
$stmt2->close();

$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE organizer_id = ? AND status = 'Rejected'");
$stmt2->bind_param("i", $organizer_id);
$stmt2->execute();
$rejectedResult = $stmt2->get_result();
$rejectedCount = $rejectedResult->fetch_assoc()['total'];
$stmt2->close();

include("header.php");
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

  <?php } ?>
<?php } else { ?>
  <p>No events found for this status.</p>
<?php } ?>

</div>

<?php include("footer.php"); ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>