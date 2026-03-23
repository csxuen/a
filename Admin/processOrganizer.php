<?php
    session_start();
    include "conn.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST['email'];
        $action = $_POST['action'];

        if ($action === "approve") {
            $sql = "UPDATE user SET user_status='Active' WHERE user_email='$email'";
        } else if ($action === "reject") {
            $sql = "UPDATE user SET user_status='Rejected' WHERE user_email='$email'";
        }

        mysqli_query($dbConn, $sql);

        header("Location: OrganizerApproval.php");
        exit();
    }
?>