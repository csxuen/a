<?php
session_start();
include "conn.php";

if(!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

$userEmail = $_SESSION['user_email'];

$sql = "SELECT * FROM user WHERE user_email = '$userEmail'";
$result = mysqli_query($dbConn, $sql);

if($result && mysqli_num_rows($result) == 1){
    $user = mysqli_fetch_assoc($result);

    $profilePic = $user['user_profilePicture'] ?? "defaultProfile.png";
}
else{
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">
<?php 
include("header.php"); 
?>

<main class="container profile-page">

  <h1 class="profile-title">Profile</h1>

  <section class="profile-card">

    <!-- Top header area -->
    <div class="profile-top">
      <img class = "profile-avatar" src="<?php echo $profilePic; ?>" alt="Profile Picture">
      <div class="profile-name"><?php echo $user['user_fullname']; ?></div>
    </div>

    <!-- Big account details block -->
    <div class="profile-block">
      <div class="profile-block-title">Account Details</div>
      <div class="profile-block-body">
        <div class="profile-row"><span>Username:</span><?php echo $user['user_username']; ?></div>
        <div class="profile-row"><span>Full Name:</span><?php echo $user['user_fullname']; ?></div>
        <div class="profile-row"><span>Phone:</span><?php echo $user['user_phoneNumber']; ?></div>
        <div class="profile-row"><span>Email:</span><?php echo $user['user_email']; ?></div>
      </div>
    </div>

    <!-- Lower 2-column -->
    <div class="profile-grid">

      <div class="profile-block">
        <div class="profile-block-title">Account Details</div>
        <div class="profile-block-body">
          <div class="profile-row"><span>Username:</span><?php echo $user['user_username']; ?></div>
          <div class="profile-row"><span>Full Name:</span><?php echo $user['user_fullname']; ?></div>
          <div class="profile-row"><span>Account Status:</span><?php echo $user['user_status']; ?></div>
        </div>
      </div>

    </div>

    <!-- Bottom actions -->
    <div class="profile-actions">
      <button class="profile-action-btn" onclick="location.href='logout.php'">Log Out</button>
    </div>

  </section>
  </main>
  
<?php include("footer.php"); ?>

</body>
</html>