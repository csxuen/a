<?php

include "conn.php";

$sqlEvents = "SELECT COUNT(*) as total_events FROM events";
$resEvents = mysqli_query ($dbConn, $sqlEvents);
$rowEvents = mysqli_fetch_assoc ($resEvents);
$totalEvents = $rowEvents ['total_events'];

$sqlPending = "SELECT COUNT(*) as pending_events FROM events WHERE status = 'Pending'";
$resPending = mysqli_query($dbConn, $sqlPending);
$rowPending = mysqli_fetch_assoc ($resPending);
$pendingEvents = $rowPending ['pending_events'];

$sqlParticipants = "SELECT COUNT(*) as total_participants FROM user WHERE user_role = 'Participant'";
$resParticipants = mysqli_query ($dbConn, $sqlParticipants);
$rowParticipants = mysqli_fetch_assoc ($resParticipants);
$totalParticipants = $rowParticipants ['total_participants'];

$sqlPoints = "SELECT SUM(pointsHistory_points) as total_points FROM pointshistory";
$resPoints = mysqli_query($dbConn, $sqlPoints);
$rowPointsData = mysqli_fetch_assoc($resPoints);
$totalPoints = $rowPointsData['total_points'] ?? 0;
$formattedPoints = number_format($totalPoints);

$sqlStatus = "SELECT status, COUNT(*) as count FROM events GROUP BY status";
$resStatus = mysqli_query($dbConn, $sqlStatus);
$chartData = ['Approved' => 0, 'Pending' => 0, 'Rejected' =>0];
while ($row = mysqli_fetch_assoc($resStatus)){
    $chartData[$row['status']] = (int)$row['count'];
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Admin Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <script src = "https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a class = "dashboard" href = "dashboard.php">Dashboard</a>
            <a href="eventApproval.php">Event Approval</a>
            <a href="userManagement.php">User Management</a>
            <a href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "mainContent">
            <h1>Admin Dashboard</h1>

            <p class = "desc">Manage events, users, and monitor sustainability metrics</p>

            <div class = "cards">
                <div class = "card">
                    <h3>Total Events</h3>
                    <h2><?php echo $totalEvents; ?></h2>
                    <p><?php echo $pendingEvents; ?> pending approval</p>
                </div>
                <div class = "card">
                    <h3>Total Participants</h3>
                    <h2><?php echo $totalParticipants; ?></h2>
                    <p>Registered in system</p>
                </div>
                <!-- <div class = "card">
                    <h3>Average sustainability Rating</h3>
                    <h2>4.1 / 5</h2>
                    <p>from last month</p>
                </div> -->
                <div class = "card">
                    <h3>Total Green Points Distributed</h3>
                    <h2><?php echo $formattedPoints; ?></h2>
                    <p>Lifetime impact</p>
                </div>
            </div>

            <div class = "charts">
                <div class = "chartBox">
                    <h3>Event Status</h3>
                    <div style = "width: 250px; height: 250px; margin: 0 auto;">
                        <canvas id = "eventPieChart"></canvas>
                    </div>

                </div>

                <div class = "chartBox">
                    <h3>Green Points Trend</h3>

                    <p>Bar chart here</p>
                </div>

            </div>

            <div class = "actions">
                <a href="eventApproval.php" class = "actionBtn">Approve Events</a>
                <a href="userManagement.php" class = "actionBtn">View Users</a>
                <a href="wasteReport.php" class = "actionBtn">View Waste Reports</a>
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

    // Pie Chart

    const ctx = document.getElementById('eventPieChart').getContext('2d');

    new Chart (ctx, {
        type: 'pie',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    <?php echo $chartData['Approved']?>,
                    <?php echo $chartData['Pending']?>,
                    <?php echo $chartData['Rejected']?>
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins:{
                legend: {
                    position: 'right',
                    labels:{
                        boxWidth: 15,
                        padding: 15
                    }
                }
            }
        }
    });
    
</script>

</html>