<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Waste Report</title>

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
            <a href="eventApproval.php">Event Approval</a>
            <a href="userManagement.php">User Management</a>
            <a class = "waste" href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "mainContent">
            <h1>Waste Report</h1>

            <p class = "desc">View waste collection and disposal data submitted by organizers</p>

            <h2 class = "sectionTitle">All Waste Reports</h2>

                <table class = "eventTable">
                    <tr class = "tableHeader">
                        <th>Event name</th>
                        <th>Events date</th>
                        <th>Organizer</th>
                        <th>Submitted by</th>
                        <th>Total waste</th>
                    </tr>

                    <tr>
                        <td>Tech Workshop</td>
                        <td>25 Jan 2026</td>
                        <td>Future Lab</td>
                        <td>26 Jan 2026</td>
                        <td>
                            <button class = "viewBtn"><a href="viewReport.php">View Report</a></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Park Clean Up</td>
                        <td>14 Oct 2026</td>
                        <td>UpUpUp</td>
                        <td>15 Oct 2026</td>
                        <td>
                            <button class = "viewBtn"><a href="viewReport.php" >View Report</a></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Tree Planting</td>
                        <td>9 Jan 2026</td>
                        <td>Big Tree</td>
                        <td>10 Jan 2026</td>
                        <td>
                            <button class = "viewBtn"><a href="viewReport.php">View Report</a></button>
                        </td>
                    </tr>
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