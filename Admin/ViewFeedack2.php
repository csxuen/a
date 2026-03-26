<?php
    session_start();
    include "conn.php";

    if (!isset($_GET['feedback_id'])) {
        header("Location: ViewFeedback.php");
        exit();
    }

    $feedback_id = (int) $_GET['id'];

    $sql = "SELECT f.feedback_id, f.event_id, f.user_email, f.rating, f.feedback_text, f.submitted_at, e.event_name, u.user_fullname
            FROM feedback f
            JOIN events e ON f.event_id = e.id
            JOIN user u ON f.user_id = u.user_id
            WHERE f.feedback_id = $feedback_id
           ";

    $result = mysqli_query($dbConn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $Feedback = mysqli_fetch_assoc($result);
    } else {
        header("Location: ViewFeedback.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - View Feedback</title>

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
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a class = "viewFeedback" href="ViewFeedback.php">View Feedback</a>
        </aside>

        <div class = "firstContent">
            <div class = "backContent">
                <a href="ViewFeedback.php">
                    <img class = "backIcon" src="turn-back.png" alt="Back icon"><span>Back</span>
                </a>
            </div>
            <h1>Feedback Overview</h1>
            <p>Review feedback for the user given to events</p>
            <br>
            <hr>
            <br>
            <div class = "feedbackOverview">
                <div class = "firstHeader">
                    <h2>Feedback given to <?php echo $Feedback['event_name']; ?></h2>
                </div>
                    <div class = "feedbackInfo">
                        <p class="label">User: </p>
                        <p class="value"><?php echo $Feedback['user_fullname']; ?></p>
                        
                        <p class="label">Event Name: </p>
                        <p class="value"><?php echo $Feedback['event_name']; ?></p>

                        <p class="label">Feedback: </p>
                        <p class="value">
                            <?php echo $Feedback['feedback_text']; ?>
                        </p>
                        
                        <p class="label">Rating: </p>
                        <p class="value"><?php echo $Feedback['rating']; ?></p>
                    </div>
                </div>
            </div>
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