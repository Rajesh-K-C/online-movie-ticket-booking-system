<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "online_movie_ticket_booking";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}