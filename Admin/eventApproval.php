<?php

include "conn.php";

$pendingSql = "SELECT * FROM events WHERE status = 'Pending'";
$pendingResult = mysqli_query($dbConn, $pendingSql);

if (!$pendingResult){
    die ("Pending query failed: " . mysqli_error($dbConn));
}

$allSql = "SELECT * FROM events";
$allResult = mysqli_query($dbConn, $allSql);

if (!$allResult){
    die ("All query failed: " . mysqli_error($dbConn));
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Event Approval</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class = "topBar">
        <button class = "hamburger" aria-label="Toggle menu">☰</button>
        <a href="dashboard.php">
            <img class = "logo" src="logo.png" alt="Website Logo">
        </a>
        
        <div class = "topRight">
            <a href="Profile.php">
                <img class = "icon" src="profile.png" alt="Profile icon" >
            </a>

            <a class = "logoutBtn" href="logout.php">Logout</a>
        </div>
    </div>

    <div class = "container">
        <aside class = "sideBar">
            <button class = "closeSideBar">&times;</button>
            <a href = "dashboard.php">Dashboard</a>
            <a class = "eventApproval" href="eventApproval.php">Event Approval</a>
            <a href="userManagement.php">User Management</a>
            <a href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "mainContent">
            <h1>Event Approval</h1>

            <p class = "desc">Review and manage events submitted by organizers before publication</p>

            <!-- Pending Events -->
            <h2 class = "sectionTitle">Pending Events</h2>

                <table class = "eventTable">
                    <tr class = "tableHeader">
                        <th>Event name</th>
                        <th>Event date</th>
                        <th>Event time</th>
                        <th>location</th>
                        <th>Event type</th>
                        <th>Status</th>
                        <th>Decide</th>
                    </tr>


                    <?php while ($row = mysqli_fetch_assoc($pendingResult)){?>
                    <tr>
                        <td><?php echo $row ['event_name']; ?></td>
                        <td><?php echo $row ['event_date']; ?></td>
                        <td><?php echo $row ['event_time']; ?></td>
                        <td><?php echo $row ['event_location']; ?></td>
                        <td><?php echo $row ['event_type']; ?></td>
                        <td><?php echo $row ['status']; ?></td>

                        <td>
                            <a class = "approveBtn" href = "updateEventStatus.php?id=<?php echo $row ['id']; ?>&amp;action=approve">&check;</a>
                            <a class = "rejectBtn" href = "updateEventStatus.php?id=<?php echo $row ['id'];?>&amp;action=reject">&cross;</a>
                        </td>
                    </tr>
                    <?php } ?>

                    <!-- <tr>
                        <td>Charity fun run</td>
                        <td>RunOrg</td>
                        <td>12 Mar 2026</td>
                        <td>Community</td>
                        <td>Pending</td>
                        <td>
                            <button class = "approveBtn">&check;</button>
                            <button class = "rejectBtn">&cross;</button>
                        </td>
                    </tr> -->

                </table>

                <br>

            <h2 class = "sectionTitle">All Events</h2>


                <table class = "eventTable">
                    <tr class = "tableHeader">
                        <th>Event name</th>
                        <th>Event date</th>
                        <th>Event time</th>
                        <th>location</th>
                        <th>Event type</th>
                        <th>Status</th>
                    </tr>

                    <?php while ($row = mysqli_fetch_assoc($allResult)) {?>
                    <tr>
                        <td><?php echo $row ['event_name']; ?></td>
                        <td><?php echo $row ['event_date']; ?></td>
                        <td><?php echo $row ['event_time']; ?></td>
                        <td><?php echo $row ['event_location']; ?></td>
                        <td><?php echo $row ['event_type']; ?></td>

                        <td class = "<?php
                            if ($row ['status'] == 'Approved'){
                                echo 'approved';
                            }

                            else if ($ro ['status'] == 'Rejected'){
                                echo 'rejected';
                            }
                        ?>">

                            <?php echo $row ['status']?>
                        
                        </td>
                    </tr>
                    <?php } ?>

                    <!-- <tr>
                        <td>Park Clean Up</td>
                        <td>UpUpUp</td>
                        <td>14 Oct 2026</td>
                        <td>Environment</td>
                        <td class = "rejected">Rejected</td>
                    </tr> -->

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