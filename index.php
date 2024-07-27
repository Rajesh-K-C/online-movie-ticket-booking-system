<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";
include_once "includes/connection.php";
include_once "includes/functions.php";

$cssList[] = "card.css";
head(title: "Online Movie Ticket Booking System", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <main>
        <h2>NOW SHOWING</h2>
        <section class="movie-section">
            <?php

            $sql = "SELECT m.movie_id, movie_name, description, release_date, duration, image 
            FROM movies m 
            INNER JOIN shows s ON m.movie_id = s.movie_id
            WHERE CONCAT(s.date, ' ', s.show_time) > NOW() AND ADDDATE(CURDATE(), INTERVAL 4 DAY) >= s.date GROUP BY m.movie_id ORDER BY CONCAT(s.date, ' ', s.show_time)";

            $result = mysqli_query($conn, $sql);
            ?>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <a href="booking?id=<?= $row['movie_id'] ?>">
                        <article class="movie-card">
                            <img src="assets/images/movies/<?= $row['image'] ?>" alt="<?= $row['movie_name'] ?>">
                            <div>
                                <h4>
                                    <?= strtoupper($row['movie_name']) ?>
                                </h4>
                                <p class="run-time">
                                    Releasing on:
                                    <?= $row['release_date'] ?>
                                </p>
                                <p class="run-time">Runtime:
                                    <?= getHoursMinutes($row['duration']) ?>
                                </p>
                            </div>
                        </article>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center;">Movies not Found!</p>
            <?php endif; ?>
        </section>
        <h2>COMING SOON</h2>
        <section class="movie-section">
            <?php
            $sql = "SELECT m.movie_id, m.movie_name, m.description, m.release_date, m.duration, m.image 
                    FROM movies m 
                    LEFT JOIN shows s ON m.movie_id = s.movie_id 
                    WHERE m.release_date > CURDATE() 
                    AND (s.movie_id IS NULL OR s.date >= ADDDATE(CURDATE(), INTERVAL 5 DAY))
                    ORDER BY m.release_date";
            $result = mysqli_query($conn, $sql);
            ?>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <a href="coming-soon?id=<?= $row['movie_id'] ?>">
                        <article class="movie-card">
                            <img src="assets/images/movies/<?= $row['image'] ?>" alt="<?= $row['movie_name'] ?>" srcset="">
                            <div>
                                <h4>
                                    <?= strtoupper($row['movie_name']) ?>
                                </h4>
                                <p class="run-time">
                                    Releasing on:
                                    <?= $row['release_date'] ?>
                                </p>
                                <p class="run-time">Runtime:
                                    <?= getHoursMinutes($row['duration']) ?>
                                </p>
                            </div>
                        </article>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center;">Movies not Found!</p>
            <?php endif; ?>
        </section>
    </main>
    <?php footer(); ?>
</body>

</html>