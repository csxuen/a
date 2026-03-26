<?php
    session_start();
    include "conn.php";

    $email = $_POST['email'];
    $action = $_POST['action'];

    if($action == "approve"){
        $status = "Active";
    }elseif($action == "reject"){
        $status = "Rejected";
    }else{
        header("Location: OrganizerApproval.php");
        exit();
    }

    $sql = "UPDATE user SET user_status='$status' WHERE user_email='$email'";
    mysqli_query($dbConn, $sql);

    header("Location: OrganizerApproval.php");
    exit();
?>