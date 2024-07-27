<?php
session_start();
if (!isset ($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $response = ['success' => false, 'message' => 'You are not logged in!'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset ($_GET['id']) && !empty ($_GET['id'])) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";

        $id = (int) test_input($_GET['id']);

        $sql = "SELECT release_date FROM movies WHERE movie_id=$id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $response = ['success' => true, 'data' => $row['release_date']];
        }
        if (!isset ($response)) {
            $response = ['success' => false, 'data' => 'Invalid movie id'];
        }
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
