<?php
include("session_test.php");
include("config.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: rewards.php");
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'participant') {
    header("Location: rewards.php");
    exit;
}

$participant_id = (int) $_SESSION['user_id'];
$reward_id = isset($_POST['reward_id']) ? (int) $_POST['reward_id'] : 0;

if ($reward_id <= 0) {
    header("Location: rewards.php?redeem=invalid");
    exit;
}

/* Get reward info */
$rewardStmt = $conn->prepare("
    SELECT reward_id, reward_name, points_cost
    FROM rewards
    WHERE reward_id = ? AND reward_status = 'active'
");
$rewardStmt->bind_param("i", $reward_id);
$rewardStmt->execute();
$rewardResult = $rewardStmt->get_result();

if ($rewardResult->num_rows === 0) {
    header("Location: rewards.php?redeem=invalid");
    exit;
}

$reward = $rewardResult->fetch_assoc();
$rewardName = $reward['reward_name'];
$pointsCost = (int) $reward['points_cost'];

/* Get current participant points */
$pointsStmt = $conn->prepare("
    SELECT COALESCE(SUM(points_change), 0) AS total_points
    FROM points_history
    WHERE participant_id = ?
");
$pointsStmt->bind_param("i", $participant_id);
$pointsStmt->execute();
$pointsResult = $pointsStmt->get_result();
$pointsRow = $pointsResult->fetch_assoc();

$currentPoints = (int) $pointsRow['total_points'];

if ($currentPoints < $pointsCost) {
    header("Location: rewards.php?redeem=notenough");
    exit;
}

/* Insert redemption */
$redeemStmt = $conn->prepare("
    INSERT INTO reward_redemptions (reward_id, participant_id, points_used)
    VALUES (?, ?, ?)
");
$redeemStmt->bind_param("iii", $reward_id, $participant_id, $pointsCost);
$redeemStmt->execute();

/* Deduct points */
$negativePoints = -$pointsCost;
$desc = "Redeemed reward: " . $rewardName;

$historyStmt = $conn->prepare("
    INSERT INTO points_history (participant_id, event_id, action_type, points_change, description)
    VALUES (?, NULL, 'reward_redeem', ?, ?)
");
$historyStmt->bind_param("iis", $participant_id, $negativePoints, $desc);
$historyStmt->execute();

/* Insert notification */
$title = "Reward Redeemed";
$message = "You redeemed " . $rewardName . " using " . $pointsCost . " Green Points.";

$notifStmt = $conn->prepare("
    INSERT INTO notifications (user_id, event_id, title, message, is_read)
    VALUES (?, NULL, ?, ?, 0)
");
$notifStmt->bind_param("iss", $participant_id, $title, $message);
$notifStmt->execute();

header("Location: rewards.php?redeem=success");
exit;
?>