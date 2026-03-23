<?php
    include "config.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_type = $_POST['eventType'];
    $max_participants = $_POST['max_participants'];
    $description = $_POST['description'];
    $sustainability_goals = $_POST['sustainability_goals'];

    $imageName = "";

    /* Image Upload */
    if(isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0){

        $imageName = time() . "_" . $_FILES['event_image']['name'];

    move_uploaded_file(
        $_FILES['event_image']['tmp_name'],
        "upload Event/" . $imageName
    );
    }

    /* Insert event */
    $sql = "INSERT INTO events 
    (event_name,event_date,event_time,event_location,event_type,max_participants,description,sustainability_goals,event_image,status)
    VALUES
    ('$event_name','$event_date','$event_time','$event_location','$event_type','$max_participants','$description','$sustainability_goals','$imageName','Pending')";

    $conn->query($sql);

    header("Location: dashboard.php");
    }
    ?>