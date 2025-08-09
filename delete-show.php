<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {

    if (isset($_GET['id'])) {
        include_once "includes/connection.php";
        include_once "includes/functions.php";
        $id = (int) test_input($_GET['id']);

        $result = mysqli_query($conn, "SELECT ticket_id FROM tickets t INNER JOIN shows s ON t.show_id = s.show_id WHERE CONCAT(s.date, ' ', s.show_time) > NOW() AND t.show_id = $id AND t.ticket_status = 'Booked'");
        if (mysqli_num_rows($result) > 0): ?>
            <script>
                alert("Tickets have been booked for this show. You cannot delete it until show has ended.");
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
} elseif (isset($_SESSION['role'])) {
    $_SESSION["MSG"] = ["error", "access denied."];
    header("Location: ./");
    die();
} else {
    $_SESSION["MSG"] = ["error", "access denied."];
    header("Location: ./login.php");
    die();
}