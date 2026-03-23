<?php
    include "conn.php";

    $rewardId = $_GET['id'];

    $sql = "DELETE FROM reward WHERE reward_id='$rewardId'";
    $result = mysqli_query($dbConn, $sql);

    if (mysqli_affected_rows($dbConn) > 0) {
        header("Location: redeemRewards.php");
    } else {
        echo "<script>alert('Delete failed');
                      window.location.href='redeemRewards.php';
              </script>";
    }
?>