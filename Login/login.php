<?php
    // this is to allow use the session later
    // and also to store the user info so that they will stay the page with their data when they proceed to other page
    session_start();

    if(!isset($_SESSION['user_id']) && isset($_COOKIE['user_email'])) {
        $_SESSION['user_email'] = $_COOKIE['user_email'];
        $_SESSION['user_role'] = $_COOKIE['user_role'];



        // later need to change to participant dashboard
        if($_SESSION['user_role'] === 'Participant') {
            header("Location: Participant.php");
            exit();
        }else if($_SESSION['user_role'] === 'Event Organizer') {
            header("Location: EventOrganizerDashboard.php");
            exit();
        }else if($_SESSION['user_role'] === 'Admin') {
            header("Location: http://localhost/Admin/PointsDistribution.html");
            exit();
        }
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // echo "The Value is: " . $email . "<br/>";
    // echo "The Value is: " . $password . "<br/>";

    include "conn.php";

    $sql = "SELECT * FROM user WHERE user_email = '$email'";
    $result = mysqli_query($dbConn, $sql);

    // if there is only one user
    if(mysqli_num_rows($result) == 1) {
        $User = mysqli_fetch_assoc($result);

        if($User['user_status'] === 'Deactivated') {
            header("Location: login.html?error=notfound");
            exit();
        }

        if(password_verify($password, $User['user_password'])) {
            if($User['user_role'] === 'Event Organizer' && $User['user_status'] != 'Active') {
                header("Location: login.html?error=notapproved");
                exit();
            }
            
            $_SESSION['user_id'] = $User['user_id'];
            $_SESSION['user_email'] = $User['user_email'];
            $_SESSION['user_fullname'] = $User['user_fullname'];
            $_SESSION['user_role'] = $User['user_role'];
            $_SESSION['user_profile_picture'] = $User['user_profilePicture'];

            if(isset($_POST['remember'])) {
                setcookie('user_email', $email, time() + (86400 * 30), "/");
                setcookie('user_role', $User['user_role'], time() + (86400 * 30), "/");
            }

            // later need to change to participant dashboard
            if($User['user_role'] === 'Participant') {
                header("Location: http://localhost/sustainableapp/participant.php");
                exit();
            // later need to change to eventOrganizer dashboard
            }else if($User['user_role'] === 'Event Organizer') {
                header("Location: http://localhost/EventOrg/dashboard.php");
                exit();
            }else if($User['user_role'] === 'Admin') {
                header("Location: http://localhost/Admin/dashboard.php");
                exit();
            }
        }else {
            header("Location: login.html?error=wrongpassword");
            exit();
        }
    }else {
        header("Location: login.html?error=notfound");
        exit();
    }
?>