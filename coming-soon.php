<?php
session_start();
// if (isset ($_SESSION['role'])) {
//     header("location: ./");
// }

include_once "includes/connection.php";
$id = (int) $_GET['id'];
// "SELECT movie_name, description, duration, l.language, image 
// FROM movies m INNER JOIN languages l ON m.language = l.language_id 
// WHERE movie_id = $id";
$sql =
    "SELECT movie_name, description, duration, language, image, release_date 
FROM movies
WHERE movie_id = $id";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";
include_once "includes/loginForm.php";
include_once "includes/functions.php";

$cssList[] = "booking.css";
head(title: $row['movie_name'], cssList: $cssList);
?>

<body>
    <style>
        .movie-info {
            width: 60%;
            margin: 0 auto;
        }

        .description {
            text-align: justify;
        }

        @media (max-width: 720px) {
            .movie-info {
                width: 100%;
            }
        }
    </style>
    <?php navbar(); ?>
    <main class="movie">
        <section class="movie-info">
            <figure>
                <img src="assets/images/movies/<?= $row['image'] ?>" alt="">
            </figure>
            <article>
                <h1 class="movie-name">
                    <?= $row['movie_name'] ?>
                </h1>
                <div class="duration">
                    <?= $row['duration'] ?> mins
                </div>
                <p class="description">
                    <?= $row['description'] ?>
                </p>
                <h3 class="language">Language:
                    <?= $row['language'] ?>
                </h3>
                <h4>Release Date:
                    <?= $row['release_date'] ?>
                </h4>
            </article>
        </section>

    </main>
    <?php footer(); ?>
</body>

</html>