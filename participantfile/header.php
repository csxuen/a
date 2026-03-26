<?php
$current = basename($_SERVER['PHP_SELF']); // e.g. events.php
function active($page, $current) {
  return $page === $current ? 'active' : '';
}
?>

<header class="navbar">
  <div class="container">

    <div class="logo">
      <a href="participant.php">
      <img src="images/logo.png" alt="EcoEvents Logo">
     </a>
    </div>

    <nav class="nav-links">
  <a href="participant.php" class="<?= active('participant.php', $current) ?>">Home</a>
  <a href="about.php" class="<?= active('about.php', $current) ?>">About Us</a>
  <a href="events.php" class="<?= active('events.php', $current) ?>">Events</a>
  <a href="rewards.php" class="<?= active('rewards.php', $current) ?>">Rewards</a>
  <a href="gallery.php" class="<?= active('gallery.php', $current) ?>">Event Gallery</a>
</nav>

    <div class="nav-right">

  <a href="notification.php" 
     class="notification-link <?= active('notification.php', $current) ?>" 
     aria-label="Notifications">
    <img src="images/notification-bell.png" alt="Notifications" class="notification-bell-icon">
    <span class="notification-dot"></span>
  </a>

  <a href="profile.php" class="profile-wrapper">
    <img src="images/profileicon.png" alt="Profile" class="profile-icon">
    <span class="nav-profile-name">Participant</span>
  </a>

  <a href="#" class="logout">Logout</a>

  </div>

  </div>
</header>