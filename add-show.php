<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:./");
    die();
}
include_once "includes/functions.php";
include_once "includes/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $movieID = (int) test_input($_POST['movieID']);
    $date = test_input($_POST['date']);
    $time = test_input($_POST['time']) . ":00";

    if ($movieID && $date && $time) {
        $sql = "INSERT INTO shows (date, show_time, movie_id) VALUES ('$date', '$time', '$movieID')";
        $result = mysqli_query($conn, $sql);

        $_SESSION["MSG"] = ["success", "Show Created successfully."];
        header("Location: dashboard.php?page=shows");
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";

array_unshift($cssList, "login.css");
$moduleList[] = "add-show.js";
head(title: "Add Show", cssList: $cssList);

$movies = getMoviesNameId();
?>

<body>
    <?php navbar(); ?>
    <main>
        <section style="max-width: 900px; margin-inline: auto; margin-bottom: 10px;">
            <h1>Add Show</h1>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <div class="input-control">
                    <label for="movieID">Movie Name:</label>
                    <select name="movieID" id="movieID">
                        <option value="">Select movie</option>
                        <?php foreach ($movies as $movie): ?>
                            <option value="<?= $movie['movie_id'] ?>">
                                <?= $movie['movie_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="err nameErr"></span>
                </div>
                <div class="input-control">
                    <label for="date">Date:</label>
                    <input type="date" min="<?= date("Y-m-d") ?>" max="<?= date('Y-m-d', strtotime(date("Y-m-d") . ' + 10 days')) ?>" name="date" id="date">
                    <span class="err dateErr"></span>
                </div>
                <div class="input-control">
                    <label for="time">Time</label>
                    <select name="time" id="time">
                        <option value="">Select time</option>
                        <?php foreach (SHOWTIME as $showTime): ?>
                            <option value="<?= $showTime ?>">
                                <?= $showTime ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="err timeErr"></span>
                </div>
                <input type="submit" value="Add">
            </form>
        </section>
    </main>
    <?php footer(jsList: $jsList, moduleList: $moduleList); ?>
</body>

</html>