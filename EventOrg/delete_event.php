<?php
include("config.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid event ID.");
}

$id = (int) $_GET['id'];

/* get image name first */
$result = $conn->query("SELECT event_image FROM events WHERE id = $id");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (!empty($row['event_image']) && file_exists("upload Event/" . $row['event_image'])) {
        unlink("upload Event/" . $row['event_image']);
    }
}

/* delete from database */
$delete = $conn->query("DELETE FROM events WHERE id = $id");

if ($delete) {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Delete failed: " . $conn->error;
}
?>