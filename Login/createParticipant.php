<?php
    $fullName = $_POST['fullName'];
    $email = $_POST['Email'];
    $userName = $_POST['username'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $defaultProfile = "defaultProfile.img";

    $role = "Participant";
    $organization = NULL;
    $reason = NULL;
    $document = NULL;
    $status = "Active";
    // For debug
    // echo "The Value is : " .$fullName . "<br/>";
    // echo "The Value is : " .$email . "<br/>";
    // echo "The Value is : " .$userName . "<br/>";
    // echo "The Value is : " .$phoneNumber . "<br/>";
    // echo "The Value is : " .$password . "<br/>";

    include "conn.php";

    $checkEmailSQL = "SELECT * FROM user WHERE user_email = '$email'";
    $emailResult = mysqli_query($dbConn, $checkEmailSQL);

    if(mysqli_num_rows($emailResult) > 0) {
        header("Location: CreateAccountParticipant.html?submitted=emailexists");
        exit(); // stops the script immediately
    }

    $sql = "INSERT INTO user (user_fullname, user_email, user_username, user_phoneNumber, user_password, user_profilePicture, user_role, user_organization, user_reason, user_document, user_status) 
            VALUES ('$fullName', '$email', '$userName', '$phoneNumber', '$password', '$defaultProfile', '$role', '$organization', '$reason', '$document', '$status');";

    // echo $sql;

    mysqli_query($dbConn, $sql);

    if(mysqli_affected_rows($dbConn) <= 0) {
        header("Location: CreateAccountParticipant.html?submitted=fail");
        exit();
    }

    header("Location: CreateAccountParticipant.html?submitted=success");
    exit();
?>