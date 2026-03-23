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
$rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
$feedback = trim($_POST['feedback'] ?? '');

if ($event_id <= 0 || $rating <= 0 || empty($feedback)) {
    header("Location: my_events.php?feedback=invalid");
    exit;
}

/* Check if event is completed */
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
    header("Location: my_events.php?feedback=notallowed");
    exit;
}

/* Insert feedback */
$stmt = $conn->prepare("
    INSERT INTO feedback (event_id, participant_id, rating, feedback_text)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiis", $event_id, $participant_id, $rating, $feedback);

if ($stmt->execute()) {
    header("Location: my_events.php?feedback=success");
} else {
    header("Location: my_events.php?feedback=error");
}

exit;
?>