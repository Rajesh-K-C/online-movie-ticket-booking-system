<?php
session_start();
if (!isset($_SESSION['role'])) {
    $response = ['success' => false, 'message' => "Please Login First"];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the request method is POST
    // Get JSON data from the request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (
        isset($data['op']) && !empty($data['op']) &&
        isset($data['np']) && !empty($data['np'])
    ) {
        include_once "../includes/connection.php";
        include_once "../includes/functions.php";

        $op = test_input($data['op']);
        $np = test_input($data['np']);

        $sql = "SELECT password FROM users WHERE email='{$_SESSION['email']}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($op, $row['password'])) {
                $hash = password_hash($np, PASSWORD_DEFAULT);
                $result = mysqli_query($conn, "UPDATE users SET password='$hash' WHERE email='{$_SESSION['email']}'");
                if ($result) {
                    $response = ['success' => true, 'message' => 'Password changed successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Error! Please try again.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Invalid old password'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid email'];
        }
    } else {
        $response = ['success' => false, 'message' => 'All fields required'];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

header('Content-Type: application/json');

echo json_encode($response);
