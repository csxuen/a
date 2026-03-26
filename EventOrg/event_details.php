<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$organizer_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Event not found.");
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM events WHERE id = ? AND organizer_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ii", $id, $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found or access denied.");
}

$row = $result->fetch_assoc();

include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($row['event_name']); ?> | EcoEvents</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<a href="dashboard.php" class="back-link">← Back to Dashboard</a>

<div class="event-details">

  <!-- Left side -->
  <div class="event-left">

    <div class="event-image-box"
         style="<?php
         if (!empty($row['event_image'])) {
             echo "background-image:url('upload Event/" . htmlspecialchars($row['event_image']) . "');
                   background-size:cover;
                   background-position:center;";
         } else {
             echo "background: var(--button);";
         }
         ?>">
    </div>

    <h1 class="event-title"><?php echo htmlspecialchars($row['event_name']); ?></h1>

    <div class="card about-card">
      <h2>About this Event</h2>
      <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
    </div>

    <div class="card goals-card">
      <h2>Sustainability Goals</h2>
      <p><?php echo nl2br(htmlspecialchars($row['sustainability_goals'])); ?></p>
    </div>
  </div>

  <!-- Right side -->
  <div class="event-right">
    <div class="card details-card">

      <div class="detail-row">
        <div class="detail-label">Date</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['event_date']); ?></div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Time</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['event_time']); ?></div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Location</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['event_location']); ?></div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Event Type</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['event_type']); ?></div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Max Participants</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['max_participants']); ?></div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value"><?php echo htmlspecialchars($row['status']); ?></div>
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

<?php
$stmt->close();
$conn->close();
?>