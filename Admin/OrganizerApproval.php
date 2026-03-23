<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Event Organizer Approval</title>

    <link rel="stylesheet" href="reset.css">

</head>
<body>
    <div class = "topBar">
        <button class = "hamburger" aria-label="Toggle menu">☰</button>
        <img class = "logo" src="logo.png" alt="Website Logo">
        
        <div class = "rightSide">
            <a href="http://localhost/Admin/Profile.php">
               
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
            <a class = "organizerApproval" href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <h1>Event Organizer Approval</h1>
            <p>Review and manage requests to approve users as event organizers</p>
            <br>

            <table class = "organizerApprovalTable">
                <tr class = "header">
                    <th>Username</th>
                    <th>Email</th>
                    <th>Organization Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php
                    include "conn.php";
                    
                    $sql = "SELECT * FROM user WHERE user_role = 'Event Organizer' AND user_status = 'Pending'";
                    $result = mysqli_query($dbConn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                
                <tr>
                    <td>
                        <?php echo $row['user_username']; ?>
                    </td>
                    <td>
                        <?php echo $row['user_email']; ?>
                    </td>
                    <td>
                        <?php echo $row['user_organization']; ?>
                    </td>
                    <td>
                        <?php echo $row['user_registerDate']; ?>
                    </td>
                    <td>
                        <?php echo $row['user_status']; ?>
                    </td>
                    <td>
                        <a class = "viewButton" href="OrganizerApproval2.php?email=<?php echo urlencode($row['user_email']); ?>">View</a>
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