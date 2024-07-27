<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin' || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo $_GET['id'];
    die();
}

include_once "includes/functions.php";
include_once "includes/connection.php";

$ticket_id = test_input($_GET['id']);

$query = "SELECT seat_no FROM tickets WHERE ticket_id = $ticket_id AND ticket_status='Booked'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE tickets SET ticket_status = 'Checkin' WHERE ticket_id = $ticket_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION["MSG"] = ["success", "Checkin successfully."];
    } else {
        $_SESSION["MSG"] = ["error", "Checkin failed."];
    }
} else {
    $_SESSION["MSG"] = ["error", "Ticket not found."];
}

header("location: dashboard?page=checkin");
die();