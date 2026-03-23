<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="Dashboard">
<?php 
include("config.php");
include("header.php"); 
?>

<div class="MADiscription">
    <H1>Mark Attendance</H1>

  <div class="tabs-Attendance-margin">
    <div class="tabs-Attendance">All</div>
    <div class="tabs-Attendance">Completed</div>
    <div class="tabs-Attendance">Uncompleted </div>
  </div>
</div>

  <div class="events">

<?php
$result = $conn->query("SELECT * FROM events ORDER BY created_at DESC");

while($row = $result->fetch_assoc()){
?>

<div class="event-card">
    <div class="event-image"
       style="<?php
       if (!empty($row['event_image'])) {
           echo "background-image:url('upload Event/".$row['event_image']."');
                 background-size:cover;
                 background-position:center;";
       } else {
           echo "background: var(--button);";
       }
       ?>">

    <div class="badge"><?php echo $row['event_type']; ?></div>

    <div class="status <?php echo strtolower($row['status']); ?>">
      <?php echo $row['status']; ?>
    </div>
  </div>

  <div class="event-content">
    <h3><?php echo $row['event_name']; ?></h3>
    <p><?php echo $row['description']; ?></p>
    <div class="event-info">📅 <?php echo $row['event_date']; ?></div>
    <div class="event-info">📍 <?php echo $row['event_location']; ?></div>

    <div class="event-footer">
      <span class="rating">⭐ 4.8</span>
     <a href="MarkAttendance.php?id=<?php echo $row['id']; ?>" class="view-btn">Mark Attendance</a>
    </div>
  </div>
</div>
<?php } ?>

</div>
<?php include("footer.php"); ?>
</body>
</html>