<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin' || !isset($_GET['id'])) {
    echo $_GET['id'];
    die();
}

include_once "includes/functions.php";
include_once "includes/connection.php";

$payment_id = test_input($_GET['id']);

$query = "SELECT seat_no FROM tickets WHERE payment_id = '$payment_id' AND ticket_status='Cancelled'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE tickets SET ticket_status = 'Refund' WHERE payment_id = '$payment_id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION["MSG"] = ["success", "Refund successfully."];
    } else {
        $_SESSION["MSG"] = ["error", "Refund failed."];
    }
} else {
    $_SESSION["MSG"] = ["error", "Ticket not found."];
}

header("location: dashboard?page=cancelled");
die();