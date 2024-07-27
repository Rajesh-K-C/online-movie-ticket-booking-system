<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    $response = ['success' => false, 'data' => 'access denied'];

}elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset ($_GET['query'])) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";

        $query = test_input($_GET['query']);

        $sql = "SELECT t.ticket_id, m.movie_name, t.seat_no, CONCAT(s.date,' ', s.show_time) as date_time, t.ticket_status 
        FROM tickets t 
        INNER JOIN shows s ON t.show_id=s.show_id 
        INNER JOIN movies m ON s.movie_id = m.movie_id 
        WHERE t.ticket_id='$query' OR t.seat_no='$query' ORDER BY s.date DESC, s.show_time DESC";
        $result = mysqli_query($conn, $sql);
        $num_of_rows = mysqli_num_rows($result);
        if ($num_of_rows > 0) {
            $arr = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $arr[] = $row;
            }
            $response = ['success' => true, 'data' => $arr];
        } elseif ($num_of_rows == 0) {
            $response = ['success' => true, 'data' => 'No Result Found!'];
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
