<?php
include("session_test.php");
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
      AND events.event_date >= CURDATE()
    GROUP BY events.id
    ORDER BY events.event_date ASC, events.event_time ASC
    LIMIT 3
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoEvents</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  
<?php include("header.php"); ?>

<!-- HERO SECTION -->
<section class="hero">
  <div class="container">
    <div class="hero-text">
      <h1>
        Creating a<br>
        Sustainable Future<br>
        Together
      </h1>
      <p>
        Join sustainable events, earn Green Points,<br>
        and make a positive environmental impact.
      </p>
      <a class="btn" href="events.php">View Events</a>
    </div>

    <div class="hero-image">
   <img src="images/hero-event.jpg" alt="Sustainable Event">
   </div>
  </div>
</section>

<!-- STATS -->
<section class="stats">
  <div class="container">
    <div>
      <h2>100k+</h2>
      <p>Participants Joined</p>
    </div>

    <div>
      <h2>1000+</h2>
      <p>Event Hosted</p>
    </div>

    <div>
      <h2>1000+</h2>
      <p>Waste Collected</p>
    </div>

    <div>
      <h2>99%</h2>
      <p>Good Comments</p>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how">
  <div class="container">
    <h2>How the Platform Works?</h2>

    <div class="how-cards">
      <div class="how-card">
        <div class="icon">Icon</div>
        <h3>Browse Events</h3>
        <p>View sustainable events dates, locations, and goals.</p>
      </div>

      <div class="how-card">
        <div class="icon">Icon</div>
        <h3>Earn Green Points</h3>
        <p>Attend events and submit proof to earn points.</p>
      </div>

      <div class="how-card">
        <div class="icon">Icon</div>
        <h3>Make Positive Impact</h3>
        <p>See how your actions help the environment.</p>
      </div>
    </div>
  </div>
</section>

<!-- UPCOMING EVENTS -->
<section class="events" id="upcomingEvents">
  <div class="container">
    <h2>Upcoming Sustainable Events</h2>

    <div class="event-grid">

<?php if ($result && $result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>

    <div class="event-card">
      <div class="event-img">Image</div>

      <div class="event-body">
        <h3><?= htmlspecialchars($row['event_name']) ?></h3>
        <p><?= htmlspecialchars($row['description']) ?></p>

        <ul class="event-meta">
          <li><?= date("d M Y", strtotime($row['event_date'])) ?>: <?= date("g:ia", strtotime($row['event_time'])) ?></li>
          <li><?= htmlspecialchars($row['event_location']) ?></li>
          <li><?= (int)$row['joined_count'] ?>/<?= (int)$row['max_participants'] ?> Participants</li>
          <li>+40 Green Points</li>
        </ul>

        <div class="event-actions">
          <a class="btn" href="eventdetails.php?id=<?= (int)$row['id'] ?>">View Details</a>
          <form action="join_event.php" method="POST">
            <input type="hidden" name="event_id" value="<?= (int)$row['id'] ?>">
            <button class="btn-outline" type="submit">Join Event</button>
          </form>
        </div>
      </div>
    </div>

  <?php endwhile; ?>
<?php else: ?>
  <p>No upcoming events available.</p>
<?php endif; ?>

</div>
</section>

<?php include("footer.php"); ?>

<script src="js/main.js"></script>
</body>
</html>