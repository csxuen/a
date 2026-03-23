<?php
$current = basename($_SERVER['PHP_SELF']); // e.g. events.php
function active($page, $current) {
  return $page === $current ? 'active' : '';
}
?>

<link rel="stylesheet" href="style.css">

<header class="navbar">
  <div class="logo">
  <a href="dashboard.php">
    <img src="Image/logo.png" alt="EcoEvents Logo" class="logo-img">
    </a>
  </div>
  

  <nav class="nav-links">
    <a href="dashboard.php" class="<?= active('dashboard.php', $current) ?>">Home</a>
    <a href="event_page.php" class="<?= active('event_page.php', $current) ?>">Events</a>
    <a href="AttendancePage.php" class="<?= active('AttendancePage.php', $current) ?>">Mark Attendance</a>
    <a href="feedback.php" class="<?= active('feedback.php', $current) ?>">View Feedback</a>
  </nav>

<div class="nav-right">


  <a href="Profile.php" class="profile-wrapper">
    <span class="profile-circle"></span>
    <span class="nav-profile-name">Participant</span>
  </a>

  <a href="logout.php" class="logout">Logout</a>
</div>
</header>