<?php
session_start();
if (isset ($_SESSION['role']) && $_SESSION['role'] == "admin") {
    
    if (isset ($_GET['id'])) {
        include_once "includes/connection.php";
        include_once "includes/functions.php";
        $id = (int) test_input($_GET['id']);

        $result = mysqli_query($conn, "SELECT ticket_id FROM tickets WHERE show_id = $id");
        

        if (mysqli_num_rows($result) > 0): ?>
            <script>
                alert("Tickets for this show have been booked. You cannot delete this show.");
                location.href = './dashboard?page=shows';
            </script>
            <?php
            die();
        endif;


        $sql = "DELETE FROM shows WHERE show_id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION["MSG"] = ["success", "The show with ID $id has been deleted."];
        } else {
            $_SESSION["MSG"] = ["error", "The show with ID $id does not exist."];
        }
    } else {
        $_SESSION["MSG"] = ["error", "The show ID is required."];
    }
    header("Location: dashboard?page=shows");
    die();
} elseif (isset ($_SESSION['role'])) {
    $_SESSION["MSG"] = ["error", "access denied."];
    header("Location: ./");
    die();
} else {
    $_SESSION["MSG"] = ["error", "access denied."];
    header("Location: ./login.php");
    die();
}