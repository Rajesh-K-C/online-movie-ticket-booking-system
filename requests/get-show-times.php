<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (
        isset ($_GET['date']) && !empty ($_GET['date']) &&
        isset ($_GET['movie_id']) && !empty ($_GET['movie_id'])
    ) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";

        $date = test_input($_GET['date']);
        $movie_id = (int) test_input($_GET['movie_id']);

        $sql = "SELECT show_time, show_id FROM shows 
        WHERE date='$date' AND movie_id=$movie_id AND CONCAT(date, ' ', show_time) > NOW()";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $arr = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $arr[] =[$row['show_id'], getShowTime($row['show_time'])];
            }
            $response = ['success' => true, 'data' => $arr];
        }
        if (!isset ($response)) {
            $response = ['success' => false, 'data' => 'Invalid input!'];
        }
    } else {
        $response = ['success' => false, 'data' => 'All inputs are required'];
    }
} else {
    $response = ['success' => false, 'data' => 'Invalid request method'];
}

// Set response headers
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
