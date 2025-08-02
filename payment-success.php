<?php
session_start();
if (
    !isset($_SESSION['role']) || $_SESSION['role'] != "user" ||
    !isset($_SESSION['payment_id']) || !isset($_SESSION['total_amount']) ||
    !isset($_GET['data'])
) {
    header("location: ./");
    die();
}

// q=su?data=eyJ0cmFuc2FjdGlvbl9jb2RlIjoiMDAwOVhLMyIsInN0YXR1cyI6IkNPTVBMRVRFIiwidG90YWxfYW1vdW50IjoiNDAwLjAiLCJ0cmFuc2FjdGlvbl91dWlkIjoiTVQxNzQxMzU3MTE5IiwicHJvZHVjdF9jb2RlIjoiRVBBWVRFU1QiLCJzaWduZWRfZmllbGRfbmFtZXMiOiJ0cmFuc2FjdGlvbl9jb2RlLHN0YXR1cyx0b3RhbF9hbW91bnQsdHJhbnNhY3Rpb25fdXVpZCxwcm9kdWN0X2NvZGUsc2lnbmVkX2ZpZWxkX25hbWVzIiwic2lnbmF0dXJlIjoieTJaNEZHVityYjM2OW9VU3JuNE1DVXJSemdwYStURlkzcG9mK09XZXUzOD0ifQ==

// echo print_r($_SESSION);

// $oid = $_GET['data'];
// $ $_GET['data'];
// die();
// $fId = $_GET['refId'];

$payment_id = $_SESSION['payment_id'];
$total_amount = $_SESSION['total_amount'];
$movie_id = $_SESSION['movie_id'];

unset($_SESSION['payment_id']);
unset($_SESSION['total_amount']);
unset($_SESSION['movie_id']);

// $url = "https://uat.esewa.com.np/epay/transrec";
// $data = [
//     'amt' => $total_amount,
//     'rid' => $fId,
//     'pid' => $payment_id,
//     'scd' => 'EPAYTEST'
// ];

// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// $response = curl_exec($curl);
// curl_close($curl);
include_once "./includes/connection.php";

mysqli_query($conn, "UPDATE payment SET reference_id='#', payment_status='paid' WHERE payment_id='$payment_id'");

// if (strpos($response, "Success") !== false) {
    mysqli_query($conn, "UPDATE tickets SET ticket_status='Booked' WHERE payment_id='$payment_id'");
    
    $_SESSION["MSG"] = ["success", "Booking successful."];
// } else {
//     mysqli_query($conn, "UPDATE tickets SET ticket_status='failed' WHERE payment_id='$payment_id'");
//     $_SESSION["MSG"] = ["error", "Booking failed due to detected illegal activity."];
// }

header("location: ./booking?id={$movie_id}");