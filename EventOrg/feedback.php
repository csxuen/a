<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if (!isset($_SESSION['user_role'])) {
    die("Access denied. user_role session not found.");
}

$role = strtolower(trim($_SESSION['user_role']));
if ($role !== 'organizer' && $role !== 'event organizer') {
    die("Access denied. This page is for organizers only.");
}

$organizer_id = (int) $_SESSION['user_id'];

include("header.php");

/*
feedback table:
- feedback_id
- event_id
- participant_id
- rating
- feedback_text
- submitted_at

events table:
- id
- organizer_id
- event_name

user table:
- user_id
- user_fullname
- user_profilePicture
*/

$sql = "
    SELECT
        f.feedback_id,
        f.event_id,
        f.participant_id,
        f.rating,
        f.feedback_text,
        f.submitted_at,
        e.id AS event_real_id,
        e.event_name,
        e.organizer_id,
        u.user_fullname,
        u.user_profilePicture
    FROM feedback f
    INNER JOIN events e ON f.event_id = e.id
    INNER JOIN `user` u ON f.participant_id = u.user_id
    WHERE e.organizer_id = ?
    ORDER BY f.submitted_at DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();

$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}

/* Summary */
$total = count($feedbacks);
$avg_rating = $total > 0 ? round(array_sum(array_column($feedbacks, "rating")) / $total, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<main class="container profile-page" style="max-width: 1100px;">

  <h1 class="profile-title">Feedback Management</h1>

  <section class="profile-card">

    <!-- Summary stats -->
    <div class="fb-admin-stats">
      <div class="fb-stat-box">
        <div class="fb-stat-num"><?php echo $total; ?></div>
        <div class="fb-stat-label">Total Feedback</div>
      </div>

      <div class="fb-stat-box">
        <div class="fb-stat-num">
          <?php echo $avg_rating; ?>
          <span style="font-size:20px; color:#f59e0b;">★</span>
        </div>
        <div class="fb-stat-label">Average Rating</div>
      </div>
    </div>

    <!-- Feedback list -->
    <div class="profile-block">
      <div class="profile-block-title">All Feedback</div>
      <div class="profile-block-body" style="gap: 0; padding: 0;">

        <?php if (empty($feedbacks)): ?>
          <div style="padding: 24px; text-align: center; color: #888; font-size: 14px;">
            No feedback submitted yet for your events.
          </div>

        <?php else: ?>

          <div class="fb-admin-row fb-admin-header">
            <div class="fb-col-name">Participant</div>
            <div class="fb-col-event">Event</div>
            <div class="fb-col-rating">Rating</div>
            <div class="fb-col-comment">Comment</div>
            <div class="fb-col-date">Date</div>
          </div>

          <?php foreach ($feedbacks as $fb): ?>
            <?php
              $avatar = !empty($fb["user_profilePicture"])
                  ? "uploads/profile/" . $fb["user_profilePicture"]
                  : "Image/defaultProfile.png";
            ?>
            <div class="fb-admin-row">

              <div class="fb-col-name">
                <img src="<?php echo htmlspecialchars($avatar); ?>"
                     alt="Participant"
                     class="fb-avatar-img"
                     onerror="this.src='Image/defaultProfile.png'">
                <span><?php echo htmlspecialchars($fb["user_fullname"]); ?></span>
              </div>

              <div class="fb-col-event">
                <?php echo htmlspecialchars($fb["event_name"]); ?>
              </div>

              <div class="fb-col-rating">
                <span class="feedback-stars">
                  <?php for ($i = 1; $i <= 5; $i++) echo $i <= (int)$fb["rating"] ? "★" : "☆"; ?>
                </span>
                <span class="fb-rating-num">(<?php echo (int)$fb["rating"]; ?>)</span>
              </div>

              <div class="fb-col-comment">
                "<?php echo htmlspecialchars($fb["feedback_text"]); ?>"
              </div>

              <div class="fb-col-date">
                <?php echo htmlspecialchars($fb["submitted_at"]); ?>
              </div>

            </div>
          <?php endforeach; ?>

        <?php endif; ?>

      </div>
    </div>

    <div class="profile-actions">
      <a href="dashboard.php" class="profile-action-btn">Back to Dashboard</a>
    </div>

  </section>

</main>

<?php include("footer.php"); ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>