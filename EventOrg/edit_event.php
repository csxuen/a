<?php
include("config.php");

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
  <title>Edit Event</title>
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<?php include("header.php"); ?>

<div class="EventDiscrption">
  <div class="EventDiscrption-text">
    <h1>Edit Event</h1>
    <p>Update your event details</p>
  </div>
</div>

<div class="modal-content" style="display:block; margin:40px auto;">
  <form method="POST" action="updateEvent.php" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="hidden" name="old_image" value="<?php echo $row['event_image']; ?>">

    <div class="form-group">
      <label>Event Name *</label>
      <input type="text" name="event_name" value="<?php echo htmlspecialchars($row['event_name']); ?>" required>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Date *</label>
        <input type="date" name="event_date" value="<?php echo $row['event_date']; ?>" required>
      </div>

      <div class="form-group">
        <label>Time</label>
        <input type="time" name="event_time" value="<?php echo $row['event_time']; ?>">
      </div>
    </div>

    <div class="form-group">
      <label>Location *</label>
      <input type="text" name="event_location" value="<?php echo htmlspecialchars($row['event_location']); ?>" required>
    </div>

    <div class="form-group">
      <label>Event Type *</label>
      <input type="text" name="eventType" value="<?php echo htmlspecialchars($row['event_type']); ?>" required>
    </div>

    <div class="form-group">
      <label>Max Participants</label>
      <input type="number" name="max_participants" min="1" value="<?php echo $row['max_participants']; ?>">
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
    </div>

    <div class="form-group">
      <label>Sustainability Goals</label>
      <textarea name="sustainability_goals"><?php echo htmlspecialchars($row['sustainability_goals']); ?></textarea>
    </div>

    <div class="form-group">
      <label>Current Image</label><br>
      <?php if (!empty($row['event_image'])) { ?>
        <img src="upload Event/<?php echo $row['event_image']; ?>" style="max-width:250px; border-radius:10px;">
      <?php } ?>
    </div>

    <div class="form-group">
      <label>Replace Image</label>
      <input type="file" name="event_image" accept="image/*">
    </div>

<div class="modal-buttons">

  <a href="event_details.php?id=<?php echo $row['id']; ?>" class="btn-secondary">
    Cancel
  </a>

  <button type="submit" class="btn-primary">
    Update Event
  </button>

</div>

  </form>
</div>

<?php include("footer.php"); ?>
</body>
</html>