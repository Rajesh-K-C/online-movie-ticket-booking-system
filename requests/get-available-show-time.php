<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $response = ['success' => false, 'message' => 'You are not logged in!'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['date']) && !empty($_GET['date'])) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";
        include_once "../includes/constants.php";

        $date = test_input($_GET['date']);
        $sql = "";
        if (
            isset($_GET['show']) && !empty($_GET['show']) &&
            is_numeric($_GET['show'])
        ) {
            $show_id = test_input($_GET['show']);
            $sql = "SELECT show_time 
            FROM shows  
            WHERE date = '{$date}' AND show_id <> $show_id";

        } else {
            $sql = "SELECT DISTINCT show_time FROM shows WHERE date='$date'";
        }
        $result = mysqli_query($conn, $sql);
        $arr = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $arr[] = date('H:i', strtotime($row['show_time']));
        }
        $arr = count($arr) > 0 ? array_values(array_diff(SHOWTIME, $arr)) : SHOWTIME;
        $response = ['success' => true, 'data' => $arr];
    } else {
        $response = ['success' => false, 'data' => 'Movie id required'];
    }
} else {
    $response = ['success' => false, 'data' => 'Invalid request method'];
}

// Set response headers
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
