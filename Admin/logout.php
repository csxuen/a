<?php
    session_start();

    // remove all session variables
    $_SESSION = [];

    // destroy the session
    session_destroy();

    // delete cookies if they exist
    if(isset($_COOKIE['user_email'])) {
        setcookie('user_email', '', time() - 3600, '/');
    }

    if(isset($_COOKIE['user_role'])) {
        setcookie('user_role', '', time() - 3600, '/');
    }

    // redirect to login page
    header("Location: http://localhost/Login/login.html");
    exit();
?>