<?php
include("session_test.php");
include("config.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: events.php");
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'participant') {
    header("Location: events.php");
    exit;
}

$participant_id = (int) $_SESSION['user_id'];
$event_id = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;

if ($event_id <= 0) {
    header("Location: events.php");
    exit;
}

/* Check event exists and is approved */
$checkEvent = $conn->prepare("
    SELECT id, event_name, max_participants 
    FROM events 
    WHERE id = ? AND status = 'Approved'
");
$checkEvent->bind_param("i", $event_id);
$checkEvent->execute();
$eventResult = $checkEvent->get_result();

if ($eventResult->num_rows === 0) {
    header("Location: events.php?join=invalid");
    exit;
}

$eventRow = $eventResult->fetch_assoc();
$eventName = $eventRow['event_name'];
$maxParticipants = (int) $eventRow['max_participants'];

/* Check if participant already joined */
$checkJoin = $conn->prepare("
    SELECT id
    FROM event_participants
    WHERE event_id = ? AND participant_id = ?
      AND participation_status IN ('joined', 'completed')
");

$checkJoin->bind_param("ii", $event_id, $participant_id);
$checkJoin->execute();
$joinResult = $checkJoin->get_result();

if ($joinResult->num_rows > 0) {
    header("Location: eventdetails.php?id=$event_id&join=already");
    exit;
}

/* Count current joined participants */
$countStmt = $conn->prepare("
    SELECT COUNT(*) AS total_joined 
    FROM event_participants 
    WHERE event_id = ? AND participation_status = 'joined'
");
$countStmt->bind_param("i", $event_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();

$currentJoined = (int) $countRow['total_joined'];

if ($currentJoined >= $maxParticipants) {
    header("Location: eventdetails.php?id=$event_id&join=full");
    exit;
}

/* Insert join record */
$insertStmt = $conn->prepare("
    INSERT INTO event_participants (event_id, participant_id, participation_status) 
    VALUES (?, ?, 'joined')
");
$insertStmt->bind_param("ii", $event_id, $participant_id);

if ($insertStmt->execute()) {

    /* Insert notification */
    $title = "Event Joined Successfully";
    $message = "You have successfully joined " . $eventName . ".";

    $notifStmt = $conn->prepare("
        INSERT INTO notifications (user_id, event_id, title, message, is_read)
        VALUES (?, ?, ?, ?, 0)
    ");
    $notifStmt->bind_param("iiss", $participant_id, $event_id, $title, $message);
    $notifStmt->execute();

    header("Location: eventdetails.php?id=$event_id&join=success");
    exit;
} else {
    header("Location: eventdetails.php?id=$event_id&join=error");
    exit;
}
?>