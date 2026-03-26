<?php
include("session_test.php");
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: events.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT notification_id, event_id, title, message, is_read, created_at
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoEvents | Notifications</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>

<?php include("header.php"); ?>

<main class="container notifications-page">
  <div class="notifications-header">
    <h1 class="notifications-title">Notifications</h1>
    <button class="notifications-settings-btn" type="button" aria-label="Notification settings">⚙</button>
  </div>

  <section class="notification-group">
    <h2 class="notification-group-title">Your Notifications</h2>

    <div class="notifications-list">

      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <?php
        $link = "#";
        if (!empty($row['event_id'])) {
            $link = "eventdetails.php?id=" . (int)$row['event_id'];
        }

        $isUnread = (int)$row['is_read'] === 0;

        $iconClass = "type-event";
        $iconSymbol = "📅";

        if (stripos($row['title'], 'Reward') !== false) {
            $iconClass = "type-reward";
            $iconSymbol = "🎁";
        } elseif (stripos($row['title'], 'Points') !== false) {
            $iconClass = "type-points";
            $iconSymbol = "⭐";
        } elseif (stripos($row['title'], 'Joined') !== false || stripos($row['title'], 'Success') !== false) {
            $iconClass = "type-success";
            $iconSymbol = "✅";
        }

        $timeText = date("d M Y, g:i A", strtotime($row['created_at']));
        ?>

        <a href="<?= $link ?>" class="notification-link-card">
        <article class="notification-item <?= $isUnread ? 'unread' : '' ?>">
            <div class="notification-left">
            <div class="notification-avatar <?= $iconClass ?>"><?= $iconSymbol ?></div>
            <?php if ($isUnread): ?>
                <span class="notification-blue-dot"></span>
            <?php endif; ?>
            </div>

            <div class="notification-content">
            <h3 class="notification-item-title"><?= htmlspecialchars($row['title']) ?></h3>
            <p class="notification-message"><?= htmlspecialchars($row['message']) ?></p>
            <span class="notification-time"><?= $timeText ?></span>
            </div>

            <div class="notification-thumb">
            <img src="images/placeholder.jpg" alt="Notification image">
            </div>
        </article>
        </a>
        <?php endwhile; ?>
      <?php else: ?>
        <article class="notification-item">
          <div class="notification-content">
            <h3 class="notification-item-title">No notifications yet</h3>
            <p class="notification-message">You do not have any notifications at the moment.</p>
          </div>
        </article>
      <?php endif; ?>

    </div>
  </section>
</main>

<?php include("footer.php"); ?>

<script src="js/main.js"></script>
</body>
</html>