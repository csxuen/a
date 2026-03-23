<?php
include("config.php");
include("header.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Event not found.");
}

$id = (int) $_GET['id'];

$result = $conn->query("SELECT * FROM events WHERE id = $id");

if ($result->num_rows === 0) {
    die("Event not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $row['event_name']; ?> | EcoEvents</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<a href="dashboard.php" class="back-link">← Back to Dashboard</a>

<div class="event-details">

  <!-- Left side -->
  <div class="event-left">
<div class="event-image-box"
     style="background-image:url('upload Event/<?php echo $row['event_image']; ?>');
            background-size:cover;
            background-position:center;">
</div>

<h1 class="event-title"><?php echo $row['event_name']; ?></h1>

    <div class="card about-card">
      <h2>About this Event</h2>
      <p><?php echo nl2br($row['description']); ?></p>
    </div>

    <div class="card goals-card">
      <h2>Sustainability Goals</h2>
      <p><?php echo nl2br($row['sustainability_goals']); ?></p>
    </div>
  </div>

  <!-- Right side -->
  <div class="event-right">
  <div class="card details-card">
    <div class="detail-row">
      <div class="detail-label">Date</div>
      <div class="detail-value"><?php echo $row['event_date']; ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Time</div>
      <div class="detail-value"><?php echo $row['event_time']; ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Location</div>
      <div class="detail-value"><?php echo $row['event_location']; ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Event Type</div>
      <div class="detail-value"><?php echo $row['event_type']; ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Max Participants</div>
      <div class="detail-value"><?php echo $row['max_participants']; ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Status</div>
      <div class="detail-value"><?php echo $row['status']; ?></div>
    </div>

    <div class="event-actions">
      <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn-primary">Edit Event</a>

      <a href="delete_event.php?id=<?php echo $row['id']; ?>"
         class="btn-secondary"
         onclick="return confirm('Are you sure you want to delete this event?');">
         Delete Event
      </a>
      <a href="WasteReport.php?id=<?php echo $row['id']; ?>" class="btn-secondary">Waste Report</a>
    </div>
  </div>
</div>

</div>

<?php include("footer.php"); ?>
</body>
</html>