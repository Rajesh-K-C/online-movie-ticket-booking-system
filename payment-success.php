<?php
session_start();
if (
    !isset($_SESSION['role']) || $_SESSION['role'] != "user" ||
    !isset($_SESSION['payment_id']) || !isset($_SESSION['total_amount']) ||
    !isset($_GET['oid']) || !isset($_GET['refId'])
) {
    header("location: ./");
    die();
}

$oid = $_GET['oid'];
$fId = $_GET['refId'];

$payment_id = $_SESSION['payment_id'];
$total_amount = $_SESSION['total_amount'];

unset($_SESSION['payment_id']);
unset($_SESSION['total_amount']);

$url = "https://uat.esewa.com.np/epay/transrec";
$data = [
    'amt' => $total_amount,
    'rid' => $fId,
    'pid' => $payment_id,
    'scd' => 'EPAYTEST'
];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
include_once "./includes/connection.php";

mysqli_query($conn, "UPDATE payment SET reference_id='$fId', payment_status='paid' WHERE payment_id='$payment_id'");

if (strpos($response, "Success") !== false) {
    mysqli_query($conn, "UPDATE tickets SET ticket_status='Booked' WHERE payment_id='$payment_id'");
    
    $_SESSION["MSG"] = ["success", "Booking successful."];
} else {
    mysqli_query($conn, "UPDATE tickets SET ticket_status='failed' WHERE payment_id='$payment_id'");
    $_SESSION["MSG"] = ["error", "Booking failed due to detected illegal activity."];
}

echo "<script> history.go(-4) </script>";