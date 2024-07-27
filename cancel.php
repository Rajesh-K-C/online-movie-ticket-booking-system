<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] == "admin" || !isset($_GET['id']) || !(isset($_GET['num']) || isset($_GET['default']))) {
    header("location: ./dashboard");
    die();
}
include_once "includes/connection.php";
include_once "includes/functions.php";

$payment_id = test_input($_GET['id']);
if (isset($_GET['num'])) {
    $esewa_id = test_input($_GET['num']);
} else {
    $esewa_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT phone FROM users WHERE email='{$_SESSION['email']}'"))['phone'];
}
$email = $_SESSION['email'];

$query = "SELECT t.ticket_id FROM tickets t 
INNER JOIN users u ON t.user_id=u.user_id 
WHERE t.payment_id = '$payment_id' AND t.ticket_status = 'Booked' AND u.email = '$email'";

$result = mysqli_query($conn, $query);
$num = mysqli_num_rows($result);
if ($num > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ticket_id = $row['ticket_id'];
        mysqli_query($conn, "UPDATE `tickets` SET `ticket_status` = 'Cancelled' WHERE `ticket_id` = '$ticket_id'");
    }
    mysqli_query($conn, "INSERT INTO `refunds` (payment_id, esewa_id) VALUES ('$payment_id', '$esewa_id')");
    if ($num == 1) {
        $_SESSION["MSG"] = ["success", "Your ticket has been cancelled."];
    } else {
        $_SESSION["MSG"] = ["success", "Your tickets have been cancelled."];
    }
} else {
    $_SESSION["MSG"] = ["error", "Ticket not found."];
}

header("location: dashboard");
