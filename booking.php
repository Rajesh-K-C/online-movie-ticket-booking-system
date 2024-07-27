<?php
session_start();
include_once "includes/connection.php";
$id = (int) $_GET['id'];

$sql =
    "SELECT m.movie_name, m.description, m.duration, m.language, m.image
FROM movies m 
WHERE m.movie_id = $id";

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


array_unshift($cssList, "login.css");
$cssList[] = "booking.css";
$moduleList[] = "booking";
$moduleList[] = "login.js";
head(title: $row['movie_name'] . " Movie", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <?php displayMessage(); ?>
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
            </article>
        </section>
        <div class="movie-booking" data-price="<?= PRICE ?>">
            <?php
            $sql = "SELECT date FROM shows WHERE movie_id = $id AND CONCAT(date,' ', show_time) > NOW() AND ADDDATE(CURDATE(), INTERVAL 4 DAY) >= date  GROUP BY date";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0):
                $isActive = true;
                ?>
                <div>
                    <section class="selection">
                        <h4 class="select-date">Select Date</h4>
                        <div class="movie-date">
                            <?php while ($row = mysqli_fetch_assoc($result)):
                                $day = date("d", strtotime($row['date'])); ?>
                                <div class="day-card">
                                    <div class="date <?= $isActive ? "active" : "" ?>" data-date="<?= $row['date'] ?>"
                                        data-movieid="<?= $id ?>">
                                        <h6>
                                            <?= date("M", strtotime($row['date'])) ?>
                                        </h6>
                                        <h3>
                                            <?= $day ?>
                                        </h3>
                                        <h6>
                                            <?= date("D", strtotime($row['date'])) ?>
                                        </h6>
                                    </div>
                                </div>
                                <?php
                                $isActive = false;
                            endwhile; ?>
                        </div>
                        <h4>Time</h4>
                        <ul class="show-timing"></ul>
                    </section>
                    <section class="seat-box">
                        <div class="seat-layout">
                            <div class="status">
                                <div class="item available">Available</div>
                                <div class="item booked">Booked</div>
                                <div class="item">Selected</div>
                            </div>
                            <div class="seats">
                                <form action="payment.php" method="post">
                                    <div class="all-seats">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <section class="price">
                        <div class="total">
                            <span><span class="count">0</span> Tickets </span>
                            <div>
                                <div class="amount">0</div> Amount
                            </div>
                        </div>
                        <button type="button" class="btn">Pay with eSewa</button>
                    </section>
                </div>
            <?php else:
                echo "<p style='text-align: center; width: 100%;'>Shows not Found!</p>";
            endif; ?>
        </div>
    </main>
    <dialog>
        <span class="closeBtn" title="Close">&times;</span>
        <?php include_once "./includes/loginForms.php" ?>
    </dialog>
    <?php footer(moduleList: $moduleList); ?>
    <?php if (isset($_SESSION["MSG"])): ?>
        <script>
            alert("<?= $_SESSION['MSG'][1] ?>")
        </script>
        <?php unset($_SESSION['MSG']);
    endif; ?>
</body>

</html>