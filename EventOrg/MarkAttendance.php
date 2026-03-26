<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if (!isset($_SESSION['user_role'])) {
    die("Access denied. user_role session not found.");
}

$role = strtolower(trim($_SESSION['user_role']));

if ($role !== 'organizer' && $role !== 'event organizer') {
    die("Access denied. This page is for organizers only.");
}

$organizer_id = (int) $_SESSION['user_id'];
$event_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

echo "DEBUG event_id = " . $event_id . "<br>";
echo "DEBUG organizer_id = " . $organizer_id . "<br>";

if ($event_id <= 0) {
    die("Invalid event.");
}

/* Check event belongs to organizer, approved, and already passed */
$eventSql = "
    SELECT *
    FROM events
    WHERE id = ?
      AND organizer_id = ?
      AND status = 'Approved'
      AND (
            event_date < CURDATE()
            OR (
                event_date = CURDATE()
                AND COALESCE(NULLIF(event_time, ''), '23:59:59') < CURTIME()
            )
          )
";

$eventStmt = $conn->prepare($eventSql);

if (!$eventStmt) {
    die("SQL error (event query): " . $conn->error);
}

$eventStmt->bind_param("ii", $event_id, $organizer_id);
$eventStmt->execute();
$eventResult = $eventStmt->get_result();

if ($eventResult->num_rows === 0) {
    die("Event not found, not approved, not yours, or event has not passed yet.");
}

$event = $eventResult->fetch_assoc();

/* Save attendance */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {

    /* Get point value for Event Completion */
    $eventCompletionPoints = 0;

    $pointSql = "SELECT points_points FROM pointsdistribution WHERE points_activity = 'Event Completion' LIMIT 1";
    $pointStmt = $conn->prepare($pointSql);

    if ($pointStmt) {
        $pointStmt->execute();
        $pointResult = $pointStmt->get_result();

        if ($pointResult && $pointResult->num_rows > 0) {
            $pointRow = $pointResult->fetch_assoc();
            $eventCompletionPoints = (int)$pointRow['points_points'];
        }

        $pointStmt->close();
    }

    foreach ($_POST['status'] as $participantRowId => $status) {
        $participantRowId = (int)$participantRowId;

        if ($status !== 'present' && $status !== 'absent') {
            continue;
        }

        /* Get participant user_id and old attendance status */
        $checkSql = "
            SELECT ep.user_id, ep.attendance_status
            FROM event_participants ep
            INNER JOIN events e ON ep.event_id = e.id
            WHERE ep.id = ?
              AND ep.event_id = ?
              AND e.organizer_id = ?
              AND ep.participation_status = 'joined'
            LIMIT 1
        ";

        $checkStmt = $conn->prepare($checkSql);

        if (!$checkStmt) {
            continue;
        }

        $checkStmt->bind_param("iii", $participantRowId, $event_id, $organizer_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if (!$checkResult || $checkResult->num_rows === 0) {
            $checkStmt->close();
            continue;
        }

        $participantData = $checkResult->fetch_assoc();
        $participantId = (int)$participantData['user_id'];
        $oldAttendanceStatus = $participantData['attendance_status'];
        $checkStmt->close();

        /* Update attendance_status only */
        $updateSql = "
            UPDATE event_participants ep
            INNER JOIN events e ON ep.event_id = e.id
            SET ep.attendance_status = ?
            WHERE ep.id = ?
              AND ep.event_id = ?
              AND e.organizer_id = ?
              AND ep.participation_status = 'joined'
        ";

        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt) {
            $updateStmt->bind_param("siii", $status, $participantRowId, $event_id, $organizer_id);
            $updateStmt->execute();
            $updateStmt->close();
        }

        /* absent/pending -> present : add points */
        if ($oldAttendanceStatus !== 'present' && $status === 'present' && $eventCompletionPoints > 0) {

            $addPointSql = "
                UPDATE `user`
                SET user_point = user_point + ?
                WHERE user_id = ?
            ";

            $addPointStmt = $conn->prepare($addPointSql);

            if ($addPointStmt) {
                $addPointStmt->bind_param("ii", $eventCompletionPoints, $participantId);
                $addPointStmt->execute();
                $addPointStmt->close();
            }

            $historySql = "
                INSERT INTO pointshistory (user_id, pointsHistory_activity, pointsHistory_points, pointsHistory_time)
                VALUES (?, ?, ?, NOW())
            ";

            $activity = "Event Completion: " . $event['event_name'];
            $historyPoints = $eventCompletionPoints;

            $historyStmt = $conn->prepare($historySql);

            if ($historyStmt) {
                $historyStmt->bind_param("isi", $participantId, $activity, $historyPoints);
                $historyStmt->execute();
                $historyStmt->close();
            }
        }

        /* present -> absent : deduct points */
        if ($oldAttendanceStatus === 'present' && $status === 'absent' && $eventCompletionPoints > 0) {

            $deductPointSql = "
                UPDATE `user`
                SET user_point = GREATEST(user_point - ?, 0)
                WHERE user_id = ?
            ";

            $deductPointStmt = $conn->prepare($deductPointSql);

            if ($deductPointStmt) {
                $deductPointStmt->bind_param("ii", $eventCompletionPoints, $participantId);
                $deductPointStmt->execute();
                $deductPointStmt->close();
            }

            $historySql = "
                INSERT INTO pointshistory (user_id, pointsHistory_activity, pointsHistory_points, pointsHistory_time)
                VALUES (?, ?, ?, NOW())
            ";

            $activity = "Event Completion Reversed: " . $event['event_name'];
            $historyPoints = -$eventCompletionPoints;

            $historyStmt = $conn->prepare($historySql);

            if ($historyStmt) {
                $historyStmt->bind_param("isi", $participantId, $activity, $historyPoints);
                $historyStmt->execute();
                $historyStmt->close();
            }
        }
    }

    header("Location: MarkAttendance.php?id=" . $event_id . "&saved=1");
    exit;
}

/* Get participants who joined this event */
$listSql = "
    SELECT 
        ep.id,
        ep.joined_at,
        ep.participation_status,
        ep.attendance_status,
        u.user_id,
        u.user_fullname,
        u.user_email,
        u.user_phoneNumber,
        u.user_profilePicture
    FROM event_participants ep
    INNER JOIN `user` u ON ep.user_id = u.user_id
    WHERE ep.event_id = ?
      AND ep.participation_status = 'joined'
    ORDER BY ep.joined_at ASC
";

$listStmt = $conn->prepare($listSql);

if (!$listStmt) {
    die("SQL error (participant query): " . $conn->error);
}

$listStmt->bind_param("i", $event_id);
$listStmt->execute();
$listResult = $listStmt->get_result();

include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">

<a href="AttendancePage.php" class="back-link">← Back to Mark Attendance</a>

<div class="MarkAttEventDiscrption">
    <div class="EventDiscrption-text">
        <h1>Mark Attendance</h1>
        <p><?php echo htmlspecialchars($event['event_name']); ?></p>
    </div>
</div>

<?php if (isset($_GET['saved'])) { ?>
    <div class="event-update-notice">Attendance updated successfully.</div>
<?php } ?>

<div class="card">
    <h2 style="margin-top:0;">Participants</h2>

    <?php if ($listResult->num_rows > 0) { ?>
        <form method="POST">
            <div class="profile-block-body">
                <?php while($row = $listResult->fetch_assoc()) { 
                    $participantPic = !empty($row['user_profilePicture'])
                        ? "uploads/profile/" . $row['user_profilePicture']
                        : "defaultProfile.png";
                ?>
                    <div class="profile-row" style="align-items:center; gap:20px; flex-wrap:wrap;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <img 
                                src="<?php echo htmlspecialchars($participantPic); ?>"
                                alt="Participant"
                                class="profile-circle"
                                onerror="this.src='defaultProfile.png'"
                            >

                            <div>
                                <div style="font-weight:700;"><?php echo htmlspecialchars($row['user_fullname']); ?></div>
                                <div style="font-size:13px; color:#666;"><?php echo htmlspecialchars($row['user_email']); ?></div>
                                <div style="font-size:13px; color:#666;">Joined: <?php echo htmlspecialchars($row['joined_at']); ?></div>
                            </div>
                        </div>

                        <div style="margin-left:auto;">
                            <select name="status[<?php echo $row['id']; ?>]">
                                <option value="present" <?php echo ($row['attendance_status'] === 'present') ? 'selected' : ''; ?>>Present</option>
                                <option value="absent" <?php echo ($row['attendance_status'] === 'absent' || $row['attendance_status'] === 'pending' || empty($row['attendance_status'])) ? 'selected' : ''; ?>>Absent</option>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <div class="profile-actions" style="margin-top:20px;">
                    <button type="submit" class="profile-action-btn primary">Save Attendance</button>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <p>No participants joined this event.</p>
    <?php } ?>
</div>

<?php include("footer.php"); ?>
</body>
</html>