<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['showId']) && !empty($_GET['showId'])) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";

        $show_id = (int) test_input($_GET['showId']);
        
        $sql = "SELECT t.ticket_id FROM tickets t 
        INNER JOIN shows s ON t.show_id=s.show_id 
        WHERE s.show_id=$show_id AND t.ticket_status ='pending' AND DATE_ADD(t.date_time, INTERVAL 3 MINUTE) < NOW()";

        $date_time = mysqli_query($conn, $sql);
        while (($row = mysqli_fetch_assoc($date_time))) {
            $ticket_id = $row['ticket_id'];
            mysqli_query($conn, "DELETE FROM tickets WHERE ticket_id = $ticket_id");
        }

        $sql = "SELECT t.seat_no FROM tickets t 
        INNER JOIN shows s ON t.show_id=s.show_id 
        WHERE s.show_id=$show_id 
        AND (t.ticket_status='booked' OR (t.ticket_status ='pending' ";

        if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
            $uid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE email='{$_SESSION['email']}'"))['user_id'];
            $sql .= "AND t.user_id<>$uid";
        }
        $sql .= "))";
        $result = mysqli_query($conn, $sql);
        $num_of_rows = mysqli_num_rows($result);
        if ($num_of_rows > 0) {
            $arr = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $arr[] = $row['seat_no'];
            }
            $response = ['success' => true, 'data' => $arr];
        } elseif ($num_of_rows == 0) {
            $response = ['success' => true, 'data' => []];
        }
        if (!isset($response)) {
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
