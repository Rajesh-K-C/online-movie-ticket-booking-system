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

        $selectedSeats = $_POST['tickets'];
        $show_id = test_input($_POST['show']);

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
        $price = PRICE;

        foreach ($selectedSeats as $seat) {
            $seat_no = test_input($seat);
            mysqli_query($conn, "INSERT INTO tickets (price, payment_id, user_id, seat_no, show_id) VALUES ($price, '$payment_id', $user_id, '$seat_no', '$show_id')");
            // $sql = "";
        }

        $epay_url = "https://uat.esewa.com.np/epay/main";

        $successurl = "http://localhost/payment-success.php?q=su";
        $failedurl = "http://localhost/payment-failed.php?q=fu";
        $merchant_code = "EPAYTEST";
        ?>
        <form action="<?= $epay_url ?>" method="POST">
            <input value="<?= $total_amount ?>" name="tAmt" type="hidden">
            <input value="<?= $total_amount ?>" name="amt" type="hidden">
            <input value="0" name="txAmt" type="hidden">
            <input value="0" name="psc" type="hidden">
            <input value="0" name="pdc" type="hidden">
            <input value="<?= $merchant_code ?>" name="scd" type="hidden">
            <input value="<?= $payment_id ?>" name="pid" type="hidden">
            <input value="<?= $successurl ?>" type="hidden" name="su">
            <input value="<?= $failedurl ?>" type="hidden" name="fu">
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