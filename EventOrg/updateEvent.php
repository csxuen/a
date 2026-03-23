<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST['id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_type = $_POST['eventType'];
    $max_participants = $_POST['max_participants'];
    $description = $_POST['description'];
    $sustainability_goals = $_POST['sustainability_goals'];
    $old_image = $_POST['old_image'];

    $imageName = $old_image;

    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $imageName = time() . "_" . basename($_FILES['event_image']['name']);

        move_uploaded_file(
            $_FILES['event_image']['tmp_name'],
            "upload Event/" . $imageName
        );

        if (!empty($old_image) && file_exists("upload Event/" . $old_image)) {
            unlink("upload Event/" . $old_image);
        }
    }

    /* check current status first */
    $statusResult = $conn->query("SELECT status FROM events WHERE id = $id");
$statusRow = $statusResult->fetch_assoc();
$currentStatus = $statusRow['status'];

if ($currentStatus === 'Approved') {
    $sql = "UPDATE events SET
        event_name = '$event_name',
        event_date = '$event_date',
        event_time = '$event_time',
        event_location = '$event_location',
        event_type = '$event_type',
        max_participants = '$max_participants',
        description = '$description',
        sustainability_goals = '$sustainability_goals',
        event_image = '$imageName',
        last_updated_at = NOW(),
        update_notice = 'An approved event was updated by the organizer.',
        show_update_notice = 1
        WHERE id = $id";
} else {
    $sql = "UPDATE events SET
        event_name = '$event_name',
        event_date = '$event_date',
        event_time = '$event_time',
        event_location = '$event_location',
        event_type = '$event_type',
        max_participants = '$max_participants',
        description = '$description',
        sustainability_goals = '$sustainability_goals',
        event_image = '$imageName'
        WHERE id = $id";
}
    $conn->query($sql);

    header("Location: event_details.php?id=$id");
    exit;
}
?>