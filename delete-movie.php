<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    if (isset($_GET['id'])) {
        include_once "includes/connection.php";
        include_once "includes/functions.php";
        $id = (int) test_input($_GET['id']);

        $result = mysqli_query($conn, "SELECT ticket_id FROM tickets t JOIN shows s ON t.show_id = s.show_id WHERE s.movie_id = $id");
        
        if (mysqli_num_rows($result) > 0): ?>
            <script>
                alert("Tickets for this movie have been booked. You cannot delete this movie.");
                location.href = './dashboard?page=movies';
            </script>
            <?php
            die();
        endif;
        
        $result = mysqli_query($conn, "SELECT show_id FROM shows WHERE movie_id = $id");

        if (mysqli_num_rows($result) > 0): ?>
            <script>
                alert("This movie has shows. You cannot delete this movie.");
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