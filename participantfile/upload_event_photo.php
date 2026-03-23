<?php
include("session_test.php");
include("config.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_events.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: participant.php");
    exit;
}

$participant_id = (int) $_SESSION['user_id'];
$event_id = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;

if ($event_id <= 0) {
    header("Location: my_events.php?upload=invalid");
    exit;
}

/* ✅ Only allow completed events */
$checkStmt = $conn->prepare("
    SELECT id FROM event_participants
    WHERE event_id = ?
    AND participant_id = ?
    AND participation_status = 'completed'
");
$checkStmt->bind_param("ii", $event_id, $participant_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    header("Location: my_events.php?upload=notallowed");
    exit;
}

/* ✅ Check file */
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    header("Location: my_events.php?upload=nofile");
    exit;
}

/* ✅ Upload folder */
$uploadDir = "uploads/images/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* ✅ File info */
$fileName = basename($_FILES['image']['name']);
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$allowed = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($extension, $allowed)) {
    header("Location: my_events.php?upload=type");
    exit;
}

/* ✅ Rename file (avoid conflict) */
$newName = time() . "_" . preg_replace("/[^A-Za-z0-9\._-]/", "_", $fileName);
$targetPath = $uploadDir . $newName;

/* ✅ Move file */
if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    header("Location: my_events.php?upload=error");
    exit;
}

/* ✅ Save into database */
$stmt = $conn->prepare("
    INSERT INTO event_gallery (event_id, image_path, uploaded_by, status)
    VALUES (?, ?, ?, 'pending')
");

$stmt->bind_param("isi", $event_id, $targetPath, $participant_id);

if ($stmt->execute()) {
    header("Location: my_events.php?upload=success");
} else {
    header("Location: my_events.php?upload=dberror");
}

exit;
?>