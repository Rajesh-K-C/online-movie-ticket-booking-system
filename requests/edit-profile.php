<?php
session_start();
if (!isset($_SESSION['role'])) {
    $response = ['success' => false, 'message' => "Please Login First"];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the request method is POST
    // Get JSON data from the request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (
        isset($data['fName']) && !empty($data['fName']) &&
        isset($data['lName']) && !empty($data['lName']) &&
        isset($data['email']) && !empty($data['email']) &&
        isset($data['phone']) && !empty($data['phone'])
    ) {
        include_once "../includes/connection.php";
        include_once "../includes/functions.php";

        $fName = test_input($data['fName']);
        $lName = test_input($data['lName']);
        $email = strtolower(test_input($data['email']));
        $phone = test_input($data['phone']);

        $sql = "SELECT email, phone FROM users WHERE (email='$email' OR phone='$phone') AND email<>'{$_SESSION['email']}'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $emailExists = false;
            $phoneExists = false;

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['email'] === $email) {
                    $emailExists = true;
                }
                if ($row['phone'] === $phone) {
                    $phoneExists = true;
                }
            }

            if ($emailExists && $phoneExists) {
                $response = ['success' => false, 'message' => 'Email and phone number already exist'];
            } elseif ($emailExists) {
                $response = ['success' => false, 'message' => 'Email already exists'];
            } else {
                $response = ['success' => false, 'message' => 'Phone number already exists'];
            }
        } else {
            $sql = "UPDATE users SET `first_name` ='$fName',  `last_name`='$lName', `email`='$email', `phone`='$phone' WHERE email='{$_SESSION['email']}'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $response = ['success' => true, 'message' => 'Update successful'];
                setSession('user', $email);
            } else {
                $response = ['success' => false, 'message' => 'update unsuccessful' . mysqli_error($conn)];
            }
        }
    } else {
        // all fields are not provided
        $response = ['success' => false, 'message' => 'All fields required'];
    }
} else {
    // Invalid request method
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

// Set response headers
header('Content-Type: application/json');
// Output JSON response
echo json_encode($response);
