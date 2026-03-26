<?php
include "conn.php";

if(isset($_POST['id']) && isset($_POST['points'])) {  // Changed from 'activity' to 'id'
    $id = intval($_POST['id']);  // Get the ID
    $points = intval($_POST['points']);

    // Update using ID instead of activity
    $sql = "UPDATE pointsdistribution SET points_points = '$points' WHERE id = '$id'";

    if(mysqli_query($dbConn, $sql)) {
        header("Location: PointsDistribution.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($dbConn);
    }
} else {
    echo "Invalid input!";
}
?>