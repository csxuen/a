<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current = basename($_SERVER['PHP_SELF']);

function active($page, $current) {
    return $page === $current ? 'active' : '';
}

/* Get user name */
$userName = $_SESSION['user_fullname'] ?? 'Participant';

/* Get profile picture */
if (!empty($_SESSION['user_profilePicture'])) {
    $profilePic = "uploads/profile/" . $_SESSION['user_profilePicture'];
} else {
    $profilePic = "defaultProfile.png";
}
?>

<link rel="stylesheet" href="CSS/style.css">

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
      <img 
        src="<?php echo htmlspecialchars($profilePic); ?>" 
        alt="Profile Picture"
        class="profile-circle"
        onerror="this.src='defaultProfile.png'"
      >

      <span class="nav-profile-name">
        <?php echo htmlspecialchars($userName); ?>
      </span>
    </a>

    <a href="logout.php" class="logout">Logout</a>

  </div>
</header>