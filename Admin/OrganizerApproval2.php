<?php
    session_start();
    include "conn.php";

    // Check if email is provided
    if(!isset($_GET['email'])) {
        header("Location: OrganizerApproval.php");
        exit();
    }

    $organizerEmail = $_GET['email'];

    $sql = "SELECT * FROM user WHERE user_email = '$organizerEmail' AND user_role = 'Event Organizer'";
    $result = mysqli_query($dbConn, $sql);

    if($result && mysqli_num_rows($result) == 1) {
        $User = mysqli_fetch_assoc($result);
        $profilePic = "defaultProfile.png";
    } else {
        // Redirect back if organizer not found
        header("Location: OrganizerApproval.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Event Organizer Request Overview</title>

    <link rel="stylesheet" href="reset.css">
</head>
<body>
    <div class = "topBar">
        <button class = "hamburger" aria-label="Toggle menu">☰</button>
        <img class = "logo" src="logo.png" alt="Website Logo">
        
        <div class = "rightSide">
            <a href="Profile.php">
                <img class = "profile" src="profile.png" alt="Profile icon">
            </a>
            <a class = "logout" href="logout.php">Logout</a>
        </div>
    </div>

    <div class = "container">
        <aside class = "sideBar">
            <button class = "closeSideBar">&times;</button>
            <button class = "closeSideBar">&times;</button>
            <a href = "dashboard.php">Dashboard</a>
            <a href="eventApproval.php">Event Approval</a>
            <a href="userManagement.php">User Management</a>
            <a href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a class = "organizerApproval" href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <div class = "backContent">
                <a href="OrganizerApproval.php">
                    <img class = "backIcon" src="turn-back.png" alt="Back icon"><span>Back</span>
                </a>
            </div>
            <h1>Event Organizer Request Overview</h1>
            <p>Review organizer details before approval</p>
            <br>
            <hr>
            <br>
            <div class = "requestOverview">
                <div class = "firstHeader">
                    <h2>Applicant Information</h2>
                </div>
                <div class = "applicantInfo">
                    <img src="<?php echo $profilePic; ?>" alt="Profile picture">
                    <div class = "info">
                        <h1><?php echo $User['user_fullname']; ?></h1>
                        <p>Email: <?php echo $User['user_email']; ?></p>
                        <p>Phone Number: <?php echo $User['user_phoneNumber']; ?></p>
                    </div>
                </div>
                <div class = "secondHeader">
                    <h2>Organization Details</h2>
                </div>
                <div class = "organizationInfo">
                    <p><strong>Organization Name: </strong><?php echo $User['user_organization']; ?></p>
                    <p><strong>Reason for Request: </strong><?php echo $User['user_reason']; ?></p>
                </div>
                <div class = "thirdHeader">
                    <h2>Verification Information</h2>
                </div>
                
                <?php 
                    $documentPath = !empty($User['user_document']) ? "../uploads/" . $User['user_document'] : "#";
                ?>
                <div class = "verificationInfo">
                    <p>
                        <strong>Supporting Proof: </strong>
                        <a href="<?php echo $documentPath; ?>" target="_blank">View Document</a>
                    </p>
                </div>
                <hr>
                <form class="reviewActions" method="POST" action="processOrganizer.php">
                    <input type="hidden" name="email" value="<?php echo $User['user_email']; ?>">

                    <button class="approveButton" name="action" value="approve">Approve</button>
                    <button class="rejectButton" name="action" value="reject">Reject</button>
                </form>
            </div>
        </div>
</body>

<script>
    // declare a variable
    const menuButton = document.querySelector('.hamburger');
    const sideBar = document.querySelector('.sideBar');

    // Adds click evemt lister, so when we click, it toggles the active class on the sidebar
    menuButton.addEventListener('click', () => {
        // Toggle = if the class not there, it adds it -> sidebar slides in
        // if the class exists, it remove it -> sidebar slides out
        sideBar.classList.toggle('active');
    });

    const closeButton = document.querySelector('.closeSideBar');

    closeButton.addEventListener('click', () => {
        // when user click the close button, it removes the active class from the sidebar -> sidebar hides
        sideBar.classList.remove('active');
    });
</script>

</html>