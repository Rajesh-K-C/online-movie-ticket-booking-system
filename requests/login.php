<?php
session_start();
if (isset ($_SESSION['role'])) {
    $response = ['success' => false, 'message' => 'You are already logged in.'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from the request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Check if email and password are provided
    if (
        isset ($data['email']) && !empty ($data['email']) &&
        isset ($data['password']) && !empty ($data['password'])
    ) {
        include_once "../includes/functions.php";
        include_once "../includes/connection.php";

        $email = strtolower(test_input($data['email']));
        $password = test_input($data['password']);


        $sql = "SELECT password FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $response = ['success' => true, 'message' => 'Login successful'];
                setSession('user', $email);
            }
        } // If no match found
        if (!isset ($response)) {
            $response = ['success' => false, 'message' => 'Invalid email or password'];
        }
    } else {
        // Email or password not provided
        $response = ['success' => false, 'message' => 'Email and password are required'];
    }
} else {
    // Invalid request method
    $response = ['success' => false, 'message' => 'Invalid request method'];
}


// Set response headers
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
