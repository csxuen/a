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
        $User = mysqli_fetch_assoc($result);

        $profilePic = "defaultProfile.png";
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
    <title>Eco Events Admin Page - Profile</title>

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
            <a href = "dashboard.php">Dashboard</a>
            <a href="eventApproval.php">Event Approval</a>
            <a href="userManagement.php">User Management</a>
            <a href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <h1>Profile</h1>
            <br>
            <div class = "profileContent">
                <div class="topProfile">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Picture">
                    <h2><?php echo $User['user_fullname']; ?></h2>
                </div>
                
                <div class = "firstHeader">
                    <h3>Account Information</h3>
                </div>
                <div class = "accountInfo">
                    <p><strong>Username: </strong><?php echo $User['user_username']; ?></p>
                    <p><strong>Full Name: </strong><?php echo $User['user_fullname']; ?></p>
                    <p><strong>Phone: </strong><?php echo $User['user_phoneNumber']; ?></p>
                    <p><strong>Email: </strong><?php echo $User['user_email']; ?></p>
                </div>       
                <div class = "profileBottom">
                    <a class = "logout2" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    const menuButton = document.querySelector('.hamburger');
    const sideBar = document.querySelector('.sideBar');

    menuButton.addEventListener('click', () => {
        sideBar.classList.toggle('active');
    });

    const closeButton = document.querySelector('.closeSideBar');

    closeButton.addEventListener('click', () => {
        sideBar.classList.remove('active');
    })
</script>

</html>