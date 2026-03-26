<?php
session_start();
include("config.php");
include("header.php");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if (!isset($_SESSION['user_role'])) {
    die("No role found in session");
}

$role = strtolower(trim($_SESSION['user_role']));

if ($role !== 'organizer' && $role !== 'event organizer') {
    die("Access denied - your role is: " . $_SESSION['user_role']);
}

$organizer_id = (int) $_SESSION['user_id'];

$sql = "
    SELECT 
        e.*,
        COUNT(ep.id) AS total_participants,
        SUM(CASE WHEN ep.participation_status = 'present' THEN 1 ELSE 0 END) AS total_present,
        SUM(CASE WHEN ep.participation_status = 'absent' THEN 1 ELSE 0 END) AS total_absent
    FROM events e
    LEFT JOIN event_participants ep ON e.id = ep.event_id
    WHERE e.organizer_id = ?
      AND e.status = 'Approved'
      AND e.event_date <= CURDATE()
    GROUP BY e.id
    ORDER BY e.event_date DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="attendance-page">

    <div class="attendance-page-header">
        <div>
            <h1 class="attendance-page-title">Mark Attendance</h1>
            <p class="attendance-page-subtitle">Manage attendance for your completed approved events</p>
        </div>
    </div>

    <?php if ($result->num_rows > 0) { ?>
        <div class="attendance-grid">

            <?php while($row = $result->fetch_assoc()) { ?>
                <div class="attendance-card">

                    <div class="attendance-card-banner"
                         style="<?php
                         if (!empty($row['event_image'])) {
                             echo "background-image:url('upload Event/" . htmlspecialchars($row['event_image']) . "');";
                         } else {
                             echo "background: linear-gradient(135deg,#2f855a,#48bb78);";
                         }
                         ?>">

                        <div class="attendance-card-overlay"></div>

                        <div class="attendance-card-badges">
                            <span class="attendance-card-type">
                                <?php echo htmlspecialchars($row['event_type']); ?>
                            </span>
                            <span class="attendance-card-status">
                                Approved
                            </span>
                        </div>
                    </div>

                    <div class="attendance-card-body">
                        <h3 class="attendance-card-title"><?php echo htmlspecialchars($row['event_name']); ?></h3>

                        <p class="attendance-card-desc">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>

                        <div class="attendance-meta-list">
                            <div class="attendance-meta-item">
                                <span class="attendance-meta-label">Date</span>
                                <span class="attendance-meta-value"><?php echo htmlspecialchars($row['event_date']); ?></span>
                            </div>

                            <div class="attendance-meta-item">
                                <span class="attendance-meta-label">Location</span>
                                <span class="attendance-meta-value"><?php echo htmlspecialchars($row['event_location']); ?></span>
                            </div>

                            <div class="attendance-meta-item">
                                <span class="attendance-meta-label">Joined</span>
                                <span class="attendance-meta-value"><?php echo (int)$row['total_participants']; ?></span>
                            </div>

                            <div class="attendance-meta-item">
                                <span class="attendance-meta-label">Present</span>
                                <span class="attendance-meta-value"><?php echo (int)$row['total_present']; ?></span>
                            </div>

                            <div class="attendance-meta-item">
                                <span class="attendance-meta-label">Absent</span>
                                <span class="attendance-meta-value"><?php echo (int)$row['total_absent']; ?></span>
                            </div>
                        </div>

                        <div class="attendance-card-footer">
                            <a href="MarkAttendance.php?id=<?php echo $row['id']; ?>" class="attendance-action-btn">
                                Mark Attendance
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    <?php } else { ?>
        <div class="attendance-empty">
            <h3>No events found</h3>
            <p>Only approved events that have already passed will appear here.</p>
        </div>
    <?php } ?>

</div>

<?php include("footer.php"); ?>