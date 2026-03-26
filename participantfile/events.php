<?php
include("config.php");

$sql = "
    SELECT 
        events.*,
        COUNT(event_participants.id) AS joined_count
    FROM events
    LEFT JOIN event_participants 
        ON events.id = event_participants.event_id
        AND event_participants.participation_status = 'joined'
    WHERE events.status = 'Approved'
    GROUP BY events.id
    ORDER BY events.event_date ASC, events.event_time ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEvents | Events</title>

  <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include("header.php"); ?>

<!-- =========================
     EVENTS HERO (Discover)
========================= -->
<section class="events-hero">
  <div class="container">
  <h1>Discover<br>Sustainable Events</h1>

  <p>
    Find the sustainable events happening near you and join events to make a
    positive impact to our earth and earn green points to redeem rewards.
  </p>

  <div class="events-hero-actions">
    <a class="btn" href="my_events.php">My Events</a>
    <a class="btn" href="rewards.php">My Green Points</a>

  </div>
  </div>
</section>

<!--Search filter-->
<section class="events-toolbar">
  <div class="container toolbar-inner">

    <div class="search-wrapper">
      <input 
        type="text" 
        id="eventSearch"
        placeholder="Search events by name or location"
      >
      <span class="search-icon">🔍</span>
    </div>

    <select id="eventFilter" class="events-filter">
      <option value="all">All Categories</option>
      <option value="clean-up">Clean-up</option>
      <option value="workshop">Workshop</option>
      <option value="planting">Planting</option>
    </select>

  </div>
</section>

<!-- =========================
     UPCOMING EVENTS LIST (cards)
========================= -->
<section class="events-page">
  <div class="container">
  <h2>Upcoming Sustainable Events</h2>

  <div class="events-grid">

  <?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>

  <div class="event-card"
     data-name="<?= htmlspecialchars(strtolower($row['event_name'])) ?>"
     data-location="<?= htmlspecialchars(strtolower($row['event_location'])) ?>"
     data-category="<?= htmlspecialchars(strtolower($row['event_type'])) ?>">

  <div class="event-img">Image</div>

  <div class="event-body">
    <h3><?= htmlspecialchars($row['event_name']) ?></h3>
    <p><?= htmlspecialchars($row['description']) ?></p>

    <ul class="event-meta">
      <li><strong>Date:</strong> <?= date("d M Y", strtotime($row['event_date'])) ?></li>
      <li><strong>Location:</strong> <?= htmlspecialchars($row['event_location']) ?></li>
      <li><strong>Participation:</strong> <?= (int)$row['joined_count'] ?>/<?= (int)$row['max_participants'] ?></li>
      <li><strong>Category:</strong> <?= htmlspecialchars($row['event_type']) ?></li>
    </ul>

    <div class="event-actions">
      <a class="btn-outline" href="eventdetails.php?id=<?= (int)$row['id'] ?>">View Details</a>
    </div>
  </div>
</div>

<?php
    }
} else {
    echo "<p>No events available.</p>";
}
?>

</div>
</div>
</section>

<?php include("footer.php"); ?>

<script src="js/main.js"></script>
</body>
</html>
