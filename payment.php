<?php

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("location: ./");
    die();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if seats data is submitted
    if (isset($_POST['tickets']) && isset($_POST['show'])) {
        include_once './includes/functions.php';
        include_once './includes/connection.php';
        include_once './includes/constants.php';

        $seats = [
            "A1",
            "A2",
            "A3",
            "A4",
            "A5",
            "A6",
            "A7",
            "A8",
            "A9",
            "B1",
            "B2",
            "B3",
            "B4",
            "B5",
            "B6",
            "B7",
            "B8",
            "B9",
            "C1",
            "C2",
            "C3",
            "C4",
            "C5",
            "C6",
            "C7",
            "C8",
            "C9",
            "D1",
            "D2",
            "D3",
            "D4",
            "D5",
            "D6",
            "D7",
            "D8",
            "D9",
            "E1",
            "E2",
            "E3",
            "E4",
            "E5",
            "E6",
            "E7",
            "E8",
            "E9",
            "F1",
            "F2",
            "F3",
            "F4",
            "F5",
            "F6",
            "F7",
            "F8",
            "F9",
            "G1",
            "G2",
            "G3",
            "G4",
            "G5",
            "G6",
            "G7",
            "G8",
            "G9",
            "H1",
            "H2",
            "H3",
            "H4",
            "H5",
            "H6",
            "H7",
            "H8",
            "H9"
        ];
        $path = dirname((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://"
            . $_SERVER['HTTP_HOST']
            . $_SERVER['REQUEST_URI']);

        $selectedSeats = $_POST['tickets'];
        $show_id = test_input($_POST['show']);
        $movie_id = test_input($_POST['movie']);

        $email = $_SESSION['email'];

        foreach ($selectedSeats as $seat) {
            $seat_no = test_input($seat);
            if (!in_array($seat_no, $seats)) {

                $_SESSION["MSG"] = ["success", "Invalid Seat Number."];
                // echo "<script>alert('Invalid Seat Number');</script>";
                echo "<script>history.back(); </script>";
                die();
            }
        }

        $user_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM users WHERE email='$email'"))['user_id'];
        $payment_id = "MT" . time();

        $no_of_seats = count($selectedSeats);
        $total_amount = $no_of_seats * PRICE;
        mysqli_query($conn, "INSERT INTO payment (payment_id, amount) VALUES ('$payment_id', $total_amount)");


        $_SESSION['payment_id'] = $payment_id;
        $_SESSION['total_amount'] = $total_amount;
        $_SESSION['movie_id'] = $movie_id;
        $price = PRICE;

        foreach ($selectedSeats as $seat) {
            $seat_no = test_input($seat);
            mysqli_query($conn, "INSERT INTO tickets (price, payment_id, user_id, seat_no, show_id) VALUES ($price, '$payment_id', $user_id, '$seat_no', '$show_id')");
        }

        $epay_url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";

        $successurl = "{$path}/payment-success.php";
        $failedurl = "{$path}/payment-failed.php";
        $merchant_code = "EPAYTEST";
        $secret = "8gBm/:&EnhH.1/q";
        $data = "total_amount={$total_amount},transaction_uuid={$payment_id},product_code={$merchant_code}";
        $s = hash_hmac('sha256', $data, $secret, true);
        $signature = base64_encode($s);
        ?>
        <form action="<?= $epay_url ?>" method="POST" style="display: none;">
            <input type="text" id="amount" name="amount" value="<?= $total_amount ?>" required>
            <input type="text" id="tax_amount" name="tax_amount" value="0" required>
            <input type="text" id="total_amount" name="total_amount" value="<?= $total_amount ?>" required>
            <input type="text" id="transaction_uuid" name="transaction_uuid" value="<?= $payment_id ?>" required>
            <input type="text" id="product_code" name="product_code" value="<?= $merchant_code ?>" required>
            <input type="text" id="product_service_charge" name="product_service_charge" value="0" required>
            <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
            <input type="text" id="success_url" name="success_url" value="<?= $successurl ?>" required>
            <input type="text" id="failure_url" name="failure_url" value="<?= $failedurl ?>" required>
            <input type="text" id="signed_field_names" name="signed_field_names"
                value="total_amount,transaction_uuid,product_code" required>
            <input type="text" id="signature" name="signature" value="<?= $signature ?>" required>
            <input value="Submit" type="submit">
        </form>

        <script>
            document.querySelector('form').submit();
        </script>

        <?php
        echo "Tickets Booked";
    } else {
        echo "Invalid Request";
    }
} else {
    header("Location: ./");
    exit;
}