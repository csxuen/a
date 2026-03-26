
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
            <a href="http://localhost/Assignment/Admin/Profile.php">
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
            <a  href="OrganizerApproval.php">Event Organizer Approval</a>
            <a href="ViewFeedback.php">View Feedback</a>
            <a class = "redeemRewards" href="redeemRewards.php">Rewards</a>
        </aside>

        <div class = "firstContent">
            <h1>Rewards</h1>
            <p>The rewards that the user can redeem using their green points.</p>
            <br>

            <button class = "addRewards">
                <img src="plus.png" alt="Add icon">
                <p>Add Rewards</p>
            </button>

            <table class = "redeemRewardsTable">
                <tr class = "header">
                    <th>Reward Name</th>
                    <th>Points Needed</th>
                    <th>Quantity left</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php
                    include "conn.php";

                    $sql = "SELECT * FROM reward";
                    $result = mysqli_query($dbConn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                ?>

                
                <tr data-id="<?php echo $row['reward_id']; ?>">
                    <td>
                        <?php echo $row['reward_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['reward_points']; ?>
                    </td>
                    <td>
                        <?php echo $row['reward_quantity']; ?>
                    </td>
                    <td>
                        <?php 
                            $documentPath = !empty($row['reward_image']) ? "../uploads/" . $row['reward_image'] : "#";
                        ?>
                        <a href="<?php echo $documentPath; ?>" target="_blank">View Image</a>
                    </td>
                    <td>
                        <?php echo $row['reward_status']; ?>
                    </td>
                    
                    <td>
                        <a class = "editButton">Edit</a>
                        <a class = "deleteButton">Delete</a>
                    </td>
                </tr>

                <?php
                    }
                ?>

            </table>
        </div>
    </div>

    <!-- Pop up message after the user click add or view -->
    <div class = "popupOverlay" id = "popupOverlay">
        <div class = "popupBox">
            <a href="redeemRewards.php" class="closeButton">&times;</a>
            <h2>Add New Reward</h2>
            <form action="redeem.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="rewardId" name="rewardId" value="">
                
                <div class = "form">
                    <label for="rewardName">Reward Name: </label>
                    <input type="text" id = "rewardName" name = "rewardName" required>
                </div>
                <div class = "form">
                    <label for="pointsNeeded">Points Needed: </label>
                    <input type="text" id = "pointsNeeded" name = "pointsNeeded" required>
                </div>
                <div class = "form">
                    <label for="quantity">Quantity: </label>
                    <input type="text" id = "quantity" name = "quantity" required>
                </div>
                <div class="form">
                    <label for="image">Reward Image: </label>
                    <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png">
                </div>
                <div class = "form">
                    <label for="status">Status: </label>
                    <select name="status" id="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <button type="reset" name = "resetReward" class = "resetButton">Reset</button>
                <button type="submit" name = "submitReward" class = "submitButton">Save Reward</button>
                
            </form>
            
            <a href="redeemRewards.php" class="backButton">Back</a>
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



    // --- Modal Logic ---
    const popupOverlay = document.getElementById('popupOverlay');
    const addRewardsBtn = document.querySelector('.addRewards');
    const closeBtn = document.querySelector('.closeButton');
    const modalTitle = document.querySelector('.popupBox h2');
    const rewardForm = document.querySelector('.popupBox form');
    const rewardIdInput = document.getElementById('rewardId');
    const imageInput = document.getElementById('image');

    // 1. Open Modal for ADDING a new reward
    addRewardsBtn.addEventListener('click', () => {
        rewardForm.reset(); // Clear any old text in the fields
        rewardIdInput.value = "";
        modalTitle.innerText = "Add New Reward";
        imageInput.required = true;
        popupOverlay.style.display = 'flex';
    });

    

    // 2. Open Modal for EDITING an existing reward
    // Find all edit buttons on the page
    const editButtons = document.querySelectorAll('.editButton');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Stop the link from jumping the page
            imageInput.required = false;

            // Find the closest table row (tr) to the button that was clicked
            const row = this.closest('tr');
            const rewardId = row.getAttribute('data-id');
            rewardIdInput.value = rewardId;

            // Extract the text from the columns (td) in that row. 
            // .trim() removes any extra invisible spaces from your HTML formatting.
            const rewardName = row.cells[0].innerText.trim();
            const pointsNeeded = row.cells[1].innerText.trim();
            const quantity = row.cells[2].innerText.trim();
            const status = row.cells[4].innerText.trim(); // should be "Active" or "Inactive"

            // Inject the extracted text into the form inputs
            document.getElementById('rewardName').value = rewardName;
            document.getElementById('pointsNeeded').value = pointsNeeded;
            document.getElementById('quantity').value = quantity;
            document.getElementById('status').value = status;

            // Change the title of the modal so the admin knows they are editing
            modalTitle.innerText = "Edit Reward";
            
            // Show the pop-up
            popupOverlay.style.display = 'flex';
        });
    });

    // 3. Close Modal Logic
    closeBtn.addEventListener('click', (e) => {
        e.preventDefault(); 
        popupOverlay.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === popupOverlay) {
            popupOverlay.style.display = 'none';
        }
    });

    const deleteButtons = document.querySelectorAll('.deleteButton');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const row = this.closest('tr');
            const rewardId = row.getAttribute('data-id');

            // optional confirm
            if (confirm("Delete this reward?")) {
                window.location.href = "deleteReward.php?id=" + rewardId;
            }
        });
    });
</script>

</html>