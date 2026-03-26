<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

$userEmail = $_SESSION['user_email'];

$sql = "SELECT * FROM user WHERE user_email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($dbConn));
}

mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Profile picture from database
    $profilePic = !empty($user['user_profilePicture']) ? $user['user_profilePicture'] : "defaultProfile.png";

    // If your images are stored inside a folder, change this path
    // Example: "uploads/profile/" . $profilePic
    $profilePicPath = $profilePic;

} else {
    header("Location: login.html");
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | EcoEvents</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<?php include("header.php"); ?>

<main class="container profile-page">

    <h1 class="profile-title">Profile</h1>

    <section class="profile-card">

        <!-- Top -->
        <div class="profile-top">
            <img class="profile-avatar"
                 src="<?php echo htmlspecialchars($profilePicPath); ?>"
                 alt="Profile Picture"
                 onerror="this.src='defaultProfile.png'">

            <div>
                <div class="profile-name"><?php echo htmlspecialchars($user['user_fullname']); ?></div>
                <div class="profile-subtitle"><?php echo htmlspecialchars($user['user_role']); ?></div>
            </div>
        </div>

        <!-- Main Account Details -->
        <div class="profile-block">
            <div class="profile-block-title">Account Details</div>
            <div class="profile-block-body">
                <div class="profile-row"><span>User ID:</span> <?php echo htmlspecialchars($user['user_id']); ?></div>
                <div class="profile-row"><span>Username:</span> <?php echo htmlspecialchars($user['user_username']); ?></div>
                <div class="profile-row"><span>Full Name:</span> <?php echo htmlspecialchars($user['user_fullname']); ?></div>
                <div class="profile-row"><span>Email:</span> <?php echo htmlspecialchars($user['user_email']); ?></div>
                <div class="profile-row"><span>Phone:</span> <?php echo htmlspecialchars($user['user_phoneNumber']); ?></div>
            </div>
        </div>

        <!-- Extra Details -->
        <div class="profile-grid">

            <div class="profile-block">
                <div class="profile-block-title">Organization Details</div>
                <div class="profile-block-body">
                    <div class="profile-row"><span>Role:</span> <?php echo htmlspecialchars($user['user_role']); ?></div>
                    <div class="profile-row"><span>Organization:</span> <?php echo htmlspecialchars($user['user_organization']); ?></div>
                    <div class="profile-row"><span>Status:</span> <?php echo htmlspecialchars($user['user_status']); ?></div>
                </div>
            </div>

            <div class="profile-block">
                <div class="profile-block-title">Other Information</div>
                <div class="profile-block-body">
                    <div class="profile-row"><span>Reason:</span> <?php echo htmlspecialchars($user['user_reason']); ?></div>
                    <div class="profile-row"><span>Register Date:</span> <?php echo htmlspecialchars($user['user_registerDate']); ?></div>
                     <div class="profile-row"><span></span> </div>
                </div>
            </div>
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