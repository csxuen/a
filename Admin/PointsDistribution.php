<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - Points Distribution</title>

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
            <a class = "points" href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <h1>Points Distribution</h1>
            <p>Define and manage rules for awarding Green Points to users based on verified activities</p>
            <table class = "pointsDistributionTable">
                <tr class = "header">
                    <th>Activity</th>
                    <th>Description</th>
                    <th>Points</th>
                    <th>Edit</th>
                </tr>
                <?php
                    include "conn.php";

                    $sql = "SELECT * FROM pointsdistribution";
                    $result = mysqli_query($dbConn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <form action="updatePoints.php" method = "POST">
                        <td>
                            <?php echo $row['points_activity']; ?>
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">  <!-- Changed this line -->
                        </td>
                        <td>
                            <?php echo $row['points_description']; ?>
                        </td>
                        <td>
                            <span class="pointsText"><?php echo $row['points_points']; ?></span>
                            <input class="pointsInput" type="number" name="points" 
                                value="<?php echo $row['points_points']; ?>" style="display:none;">
                        </td>
                        <td>
                            <button type="button" class="editButton">Edit</button>
                            <button type="submit" class="saveButton">Save</button>
                        </td>
                    </form>
                </tr>
                <?php
                    }
                ?>
            </table>

            <br>

            <div class = "secondContent">
            <h1>Points History</h1>
            <p>View points automatically awarded by the system based on defined rules</p>
            <table class = "pointsDistributionTable">
                <tr class = "header">
                    <th>User</th>
                    <th>Activity</th>
                    <th>Points</th>
                    <th>Detected At</th>
                </tr>

                <?php
                    $historySql = "SELECT u.user_fullname, ph.pointsHistory_activity, ph.pointsHistory_points, ph.pointsHistory_time
                                   FROM pointshistory ph
                                   JOIN user u ON ph.user_id = u.user_id
                                   ORDER BY ph.pointsHistory_time DESC
                                  ";

                    $historyResult = mysqli_query($dbConn, $historySql);

                    if ($historyResult && mysqli_num_rows($historyResult) > 0) {
                        while ($historyRow = mysqli_fetch_assoc($historyResult)) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($historyRow['user_fullname']); ?></td>
                    <td><?php echo htmlspecialchars($historyRow['pointsHistory_activity']); ?></td>
                    <td>
                        <?php
                            $points = (int)$historyRow['pointsHistory_points'];
                            echo ($points > 0 ? "+" : "") . $points;
                        ?>
                    </td>
                    <td>
                        <?php echo date("d M Y, g:iA", strtotime($historyRow['pointsHistory_time'])); ?>
                    </td>
                </tr>
                <?php
                        }
                    } else {
                ?>
                <tr>
                    <td colspan="4">No points history found.</td>
                </tr>
                <?php
                    }
                ?>
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
    
    
    const editButtons = document.querySelectorAll(".editButton");

    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            const row = this.closest("tr");
            const text = row.querySelector(".pointsText");
            const input = row.querySelector(".pointsInput");
            const save = row.querySelector(".saveButton");
            
            text.style.display = "none";      // hide text
            input.style.display = "inline";   // show input
            save.style.display = "inline";    // show save
            this.style.display = "none";      // hide edit button
        });
    });
</script>
</html>