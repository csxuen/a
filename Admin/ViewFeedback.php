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
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <h1>View Feedback</h1>
            <p>---</p>
            <br>
            <table class = "viewFeedbackTable">
                <tr class = "header">
                    <th>User</th>
                    <th>Event</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>

                <?php
                    include "conn.php";
                    
                    $sql = "SELECT f.feedback_id, f.rating, f.feedback_text, u.user_fullname, e.event_name
                            FROM feedback f
                            JOIN `user` u ON f.user_id = u.user_id
                            JOIN events e ON f.event_id = e.id
                           ";
                    $result = mysqli_query($dbConn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td>
                        <?php echo $row['user_fullname']; ?>
                    </td>
                    <td>
                        <?php echo $row['event_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['feedback_text']; ?>
                    </td>
                    <td>
                        <?php echo $row['rating']; ?>
                    </td>
                    <td>
                        <a class = "viewButton" href="feedbackOverview.php?id=<?php echo $row['feedback_id'] ?>">View</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </table>
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