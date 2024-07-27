<?php
session_start();
if (isset ($_SESSION['role'])) {
    $response = ['success' => false, 'message' => 'You are already logged in.'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the request method is POST
    // Get JSON data from the request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (
        isset ($data['fName']) && !empty ($data['fName']) &&
        isset ($data['lName']) && !empty ($data['lName']) &&
        isset ($data['email']) && !empty ($data['email']) &&
        isset ($data['phone']) && !empty ($data['phone']) &&
        isset ($data['password']) && !empty ($data['password'])
    ) {
        include_once "../includes/connection.php";
        include_once "../includes/functions.php";

        $fName = test_input($data['fName']);
        $lName = test_input($data['lName']);
        $email = strtolower(test_input($data['email']));
        $phone = test_input($data['phone']);
        $password = test_input($data['password']);


        // $password = mysqli_real_escape_string($conn, $password);


        $sql = "SELECT email, phone FROM users WHERE email='$email' OR phone='$phone'";
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
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (`first_name`, `last_name`, `email`, `phone`, `password`) VALUES ('$fName', '$lName', '$email', '$phone', '$hash')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $response = ['success' => true, 'message' => 'Registration successful'];
                setSession('user', $email);
            } else {
                $response = ['success' => false, 'message' => 'Registration unsuccessful' . mysqli_error($conn)];
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
