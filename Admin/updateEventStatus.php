<?php
include "conn.php";

if (isset($_GET ['id']) && isset ($_GET ['action'])){
    $id = intval ($_GET ['id']);
    $action = $_GET ['action'];

    // echo "id: $id, action: $action";

    if ($action == "approve"){
        $status = "Approved";
    }
    else if ($action == "deactive"){
        $status = "Deactivate";
    }
    else {
        die ("Invalid action");
    }

    $sql = "UPDATE events SET status = '$status' WHERE id = $id";

    if (mysqli_query($dbConn, $sql)){
        echo "Update success: Event status changed to '$status'";
        header ("Location: eventApproval.php");
        exit();
    }
    else{
        echo "Update failed: " . mysqli_error($dbConn);
    }
}
else{
    echo "Missing id or action";
}



?>