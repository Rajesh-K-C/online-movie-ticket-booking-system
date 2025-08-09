<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    if (isset($_GET['id'])) {
        include_once "includes/connection.php";
        include_once "includes/functions.php";
        $id = (int) test_input($_GET['id']);

        $result = mysqli_query($conn, "SELECT ticket_id FROM tickets t INNER JOIN shows s ON t.show_id = s.show_id WHERE CONCAT(s.date, ' ', s.show_time) > NOW() AND s.movie_id = $id AND t.ticket_status = 'Booked'");
        
        if (mysqli_num_rows($result) > 0): ?>
            <script>
                alert("Tickets have been booked for this movie. You cannot delete it until all its shows have ended.");
                location.href = './dashboard?page=movies';
            </script>
            <?php
            die();
        endif;

        $sql = "SELECT image, movie_name FROM movies WHERE movie_id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $filePath = "assets/images/movies/" . $row['image'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $sql = "DELETE FROM movies WHERE movie_id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION["MSG"] = ["success", "The movie '{$row['movie_name']}' has been deleted."];
        } else {
            $_SESSION["MSG"] = ["error", "The movie does not exist."];
        }
    } else {
        $_SESSION["MSG"] = ["error", "Movie id is required."];
    }
    header("Location: dashboard?page=movies");
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