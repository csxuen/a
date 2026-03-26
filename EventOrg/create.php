<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check login
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    $organizer_id = $_SESSION['user_id'];

    $event_name = $_POST['event_name'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $event_location = $_POST['event_location'] ?? '';
    $event_type = $_POST['eventType'] ?? '';
    $max_participants = $_POST['max_participants'] ?? 0;
    $description = $_POST['description'] ?? '';
    $sustainability_goals = $_POST['sustainability_goals'] ?? '';

    $imageName = "";

    /* Image Upload */
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $imageName = time() . "_" . basename($_FILES['event_image']['name']);
        $uploadPath = "upload Event/" . $imageName;

        move_uploaded_file($_FILES['event_image']['tmp_name'], $uploadPath);
    }

    /* Insert event with organizer_id */
    $sql = "INSERT INTO events 
    (organizer_id, event_name, event_date, event_time, event_location, event_type, max_participants, description, sustainability_goals, event_image, status)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "isssssisss",
        $organizer_id,
        $event_name,
        $event_date,
        $event_time,
        $event_location,
        $event_type,
        $max_participants,
        $description,
        $sustainability_goals,
        $imageName
    );

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>