<?php
session_start();
if (!isset($_SESSION['role'])) {
    $response = ['success' => false, 'data' => 'Failure'];
} elseif ($_SESSION['role'] == 'admin') {
    $response = ['success' => false, 'data' => 'Admin not allowed to book tickets'];
} else {
    $response = ['success' => true, 'data' => 'Success'];
}
// Set response headers
header('Content-Type: application/json');

// Output JSON response
echo json_encode($response);
