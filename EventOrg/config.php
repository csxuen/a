<?php

$conn = new mysqli("localhost", "root", "", "events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>