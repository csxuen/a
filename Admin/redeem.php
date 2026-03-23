<?php
    $rewardId = $_POST['rewardId'];
    $rewardName = $_POST['rewardName'];
    $pointsNeeded = $_POST['pointsNeeded'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    include "conn.php";

    $imageName = "";

    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $imageName);
    }

    if ($quantity == 0) {
        $status = "Inactive";
    }
    
    if (!empty($rewardId)) {
        if ($imageName != "") {
            $sql = "UPDATE reward 
                    SET reward_name='$rewardName', reward_points='$pointsNeeded', reward_quantity='$quantity', reward_image='$imageName', reward_status='$status'
                    WHERE reward_id='$rewardId'";
        } else {
            // keep current image
            $sql = "UPDATE reward 
                    SET reward_name='$rewardName', reward_points='$pointsNeeded', reward_quantity='$quantity', reward_status='$status'
                    WHERE reward_id='$rewardId'";
        }
    } else {
        $sql = "INSERT INTO reward(reward_name, reward_points, reward_quantity, reward_image, reward_status) 
                VALUES ('$rewardName', '$pointsNeeded', '$quantity', '$imageName', '$status');";
    }

    $result = mysqli_query($dbConn, $sql);
    
    // Check if the query itself failed (e.g., a typo in your SQL), ignoring rows affected
    if(!$result) {
        echo "<script>alert('Failed to save data!');
                      window.location.href = 'redeemRewards.php';
              </script>";
        exit();
    } else {
        header("Location: redeemRewards.php");
        exit();
    }
?>