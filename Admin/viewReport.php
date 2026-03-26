<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - View Waste Report</title>

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
            <div class = "pageHeader">
                <h1>Waste Report</h1>

                <a href="wasteReport.php" class = "backBtn">Back</a>
            </div>

            <h2 class = "eventTitle">Park Clean Up</h2>

            <div class = "reportInfo">
                <div class = "infoRow">
                    <div class = "infoLabel">Event Date:</div>
                    <div class = "infoValue">14 Oct 2026</div>
                </div>

                <div class = "infoRow">
                    <div class = "infoLabel">Organizer:</div>
                    <div class = "infoValue">UpUpUp</div>
                </div>

                <div class = "infoRow">
                    <div class = "infoLabel">Report Submitted:</div>
                    <div class = "infoValue">15 Oct 2026</div>
                </div>

                <div class = "infoRow">
                    <div class = "infoLabel">Report Status:</div>
                    <div class = "infoValue">Submitted</div>
                </div>

            </div>

            <h2 class = "sectionTitle">Waste Collected</h2>

            <div class = "tableContainer">
                <table class = "eventTable">

                <tr class = "tableHeader">
                    <th>Waste Type</th>
                    <th>Quantity</th>
                    <th>Collected</th>
                    <th>Collection Method</th>
                    <th>Disposal Method</th>
                </tr>

                <tr>
                    <td>Plastic</td>
                    <td>25</td>
                    <td>kg</td>
                    <td>Cleanup</td>
                    <td>Recycled</td>
                </tr>

                <tr>
                    <td>Paper</td>
                    <td>10</td>
                    <td>kg</td>
                    <td>Recycling</td>
                    <td>Recycled</td>
                </tr>

                <tr>
                    <td>Glass</td>
                    <td>8</td>
                    <td>kg</td>
                    <td>Cleanup</td>
                    <td>Recycled</td>
                </tr>
                </table>
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