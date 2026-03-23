<?php
    $fullName = $_POST['fullName'];
    $email = $_POST['Email'];
    $username = $_POST['username'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $defaultProfile = "defaultProfile.png";
    $role = "Event Organizer";
    $organization = $_POST['organizationName'];
    $reason = $_POST['reason'];
    $document = $_POST['document'];
    $status = "Pending";

    // echo "The Value is: " . $fullName . "<br/>";
    // echo "The Value is: " . $email . "<br/>";
    // echo "The Value is: " . $username . "<br/>";
    // echo "The Value is: " . $phoneNumber . "<br/>";
    // echo "The Value is: " . $password . "<br/>";
    // echo "The Value is: " . $document . "<br/>";
    // echo "The Value is: " . $status . "<br/>";

    include "conn.php";
    
    $checkEmailSQL = "SELECT * FROM user WHERE user_email = '$email'";
    $emailResult = mysqli_query($dbConn, $checkEmailSQL);

    if(mysqli_num_rows($emailResult) > 0) {
        header("Location: CreateAccountOrganizer.html?submitted=emailexists");
        exit(); // stops the script immediately
    }

    $sql = "INSERT INTO user (user_fullname, user_email, user_username, user_phoneNumber, user_password, user_profilePicture, user_role, user_organization, user_reason, user_document,  user_status) 
            VALUES ('$fullName', '$email', '$username', '$phoneNumber', '$password', '$defaultProfile', '$role', '$organization', '$reason', '$document', '$status');";

    // echo $sql;

    mysqli_query($dbConn, $sql);

    // if nothing was added to database it stops the script immediately and show fail message
    if(mysqli_affected_rows($dbConn) <= 0) {
        header("Location: CreateAccountOrganizer.html?submitted=fail");
        exit();
    }

    header("Location: CreateAccountOrganizer.html?submitted=success");
    exit();
?>