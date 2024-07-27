<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user' || !isset($_SESSION['payment_id'])) {
    header("location: ./");
}

include_once "includes/connection.php";
$payment_id = $_SESSION['payment_id'];

unset($_SESSION['payment_id']);
unset($_SESSION['total_amount']);

mysqli_query($conn, "DELETE FROM tickets WHERE payment_id = '$payment_id'");

mysqli_query($conn, "UPDATE payment SET payment_status='Failed'");

?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";
include_once "includes/loginForm.php";

head(title: "Transaction Failed");
?>

<body>
    <?php navbar(); ?>
    <main class="center">
        <section class="flex flex-direction-column align-items-center">
            <h1>Payment Failed</h1>
            <p>Back to homepage</p>
            <a href="./">
                <button class="btn">Home</button>
            </a>
        </section>
    </main>
    <?php footer(); ?>
</body>

</html>