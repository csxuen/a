<?php
include "conn.php";

$sql = "SELECT * FROM user";
$result = mysqli_query ($dbConn, $sql);

if(!$result){
    die("Query failed: " . mysqli_error($dbConn));
}

if(mysqli_num_rows($result) == 0){
    echo "No users found in the databse";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Events Admin Page - User Management</title>

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
            <a class = "userManagement" href="userManagement.php">User Management</a>
            <a href="wasteReport.php">Waste Report</a>
            <a href="PointsDistribution.php">Points Distribution</a>
            <a href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "mainContent">
            <h1>User Management</h1>

            <p class = "desc">Manage system users and assign roles</p>

            <h2 class = "sectionTitle">All Users</h2>

                <table class = "eventTable">
                    <tr class = "tableHeader">
                        <th>User name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php while ($row = mysqli_fetch_assoc($result)) {?>
                    <tr>
                        <td><?php echo $row ['user_fullname']; ?></td>
                        <td><?php echo $row ['user_email']; ?></td>
                        <td><?php echo $row ['user_role']; ?></td>
                        <td class="activeStatus"><?php echo $row ['user_status']; ?></td>
                        
                        <td>
                            <?php
                            if ($row ['user_status'] == 'Pending'){
                                echo "<span class = 'pendingLabel'>Waiting Approval</span>";
                            }
                            
                            elseif ($row ['user_status'] == 'Active'){?>
                                <button class = "deactivateBtn" data-id ="<?php echo $row ['user_id']?>">Deactivate</button>
                            <?php } else { ?>
                                <button class = "activeBtn" data-id ="<?php echo $row ['user_id']?>">Activate</button>
                            <?php } ?>
                        </td>
                    </tr>

                    <?php } ?>

                    <!-- <tr>
                        <td>Nick</td>
                        <td>nick@gmail.com</td>
                        <td>Event Organizer</td>
                        <td class = "activeStatus">Active</td>
                        <td>
                            <button class = "deactivateBtn">Deactivate</button>
                        </td>
                    </tr>

                    <tr>
                        <td>Sarah</td>
                        <td>sarah@gmail.com</td>
                        <td>Participant</td>
                        <td class = "deactivatedStatus">Deactivated</td>
                        <td>
                            <button class = "deactivateBtn">Deactivate</button>
                        </td>
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

    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll ('.deactivateBtn, .activeBtn');

        buttons.forEach (button => {
            button.addEventListener('click', function() {
                const userID = this.getAttribute ('data-id');
                const action = this.classList.contains('deactivateBtn') ? 'deactivate' : 'activate';

                const xhr = new XMLHttpRequest();
                xhr.open ('GET' , `updateUserStatus.php?id=${userID}&action=${action}`, true);
                xhr.onload = function() {

                    if (xhr.status == 200){
                        const statusCell = button.closest ('tr').querySelector('.statusCell');
                        if (action == 'deactivate'){
                            button.textContent = 'Activate';
                            button.classList.remove('deactivateBtn');
                            button.classList.add('activeBtn');
                            button.closest('tr').querySelector('.activeStatus').textContent = 'Deactivated';
                        } else{
                            button.textContent = 'Deactivate';
                            button.classList.remove('activeBtn');
                            button.classList.add('deactivateBtn');
                            button.closest('tr').querySelector('.activeStatus').textContent = 'Active';
                        }
                    } else{
                        alert('An error occurred while updating the user status.');
                    }
                };
                xhr.send();
            });
        });
    });
    
</script>

</html>
