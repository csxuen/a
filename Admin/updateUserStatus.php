<?php
include "conn.php";

$checkSql = "SELECT user_status FROM user WHERE user_id = $id";
$checkRes = mysqli_query($dbConn, $checkSql);
$currentUser = mysqli_fetch_asoc($checkRes);

if ($currentUser ['user_status'] == 'Pending'){
    header('HTTP/1.1 403 Forbidden');
    die ("Cannot modify a Pending user through this interface");
}

if (isset($_GET ['id']) && isset($_GET ['action'])) {
    $id = intval ($_GET ['id']);
    $action = strtolower ($_GET ['action']);

    error_log ("User ID: $id, Action: $action");

    if ($action == "deactivate"){
        $status = "Deactivated";
    }
    else if ($action == "activate"){
        $status = "Active";
    }
    else {
        header('HTTP/1.1 400 Bad Request');
        die ("Invalid action: " . htmlspecialchars($action));
    }

    $sql = "UPDATE user SET user_status = '$status' WHERE user_id = $id";

    error_log ("SQL Query: $sql");

    if (mysqli_query ($dbConn, $sql)){
        echo "Success";
    }
    else {
        error_log ("SQL Error: " . mysqli_error($dbConn));
        header ('HTTP/1.1 500 Internal Server Error');
        echo "Failed: " . mysqli_error ($dbConn);
    }
}
else {
    echo "Missing id or action";
}
?>