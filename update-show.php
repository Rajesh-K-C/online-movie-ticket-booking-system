<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:./");
    die();
}
include_once "includes/functions.php";
include_once "includes/connection.php";
date_default_timezone_set('Asia/Kathmandu');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $movieID = (int) test_input($_POST['movieID']);
    $id = (int) test_input($_POST['id']);
    $date = test_input($_POST['date']);
    $time = test_input($_POST['time']);

    if ($movieID && $date && $time) {
        $datetime = "$date $time:00";
        $sql = "UPDATE shows SET date='$date', movie_id=$movieID, show_time='$time' WHERE show_id = $id";
        $result = mysqli_query($conn, $sql);

        $_SESSION["MSG"] = ["success", "Show Updated"];
        header("Location: dashboard.php?page=shows");
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

$cssList[] = "login.css";
$moduleList[] = "update-show";
head(title: "Update Show", cssList: $cssList);

$sql = "SELECT movie_id, movie_name, release_date FROM movies ORDER BY movie_id DESC LIMIT 20";

$movies = mysqli_query($conn, $sql);

if (isset($_GET['id'])) {
    $id = (int) test_input($_GET['id']);

    $result = mysqli_query($conn, "SELECT ticket_id FROM tickets WHERE show_id = $id");

    if (mysqli_num_rows($result) > 0): ?>
        <script>
            alert("This show cannot be updated.");
            location.href = './dashboard?page=shows';
        </script>
        <?php
        die();
    endif;

    $sql = "SELECT s.movie_id, s.date, s.show_time, m.release_date 
    FROM shows s 
    INNER JOIN movies m ON s.movie_id=m.movie_id 
    WHERE show_id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $sql = "SELECT show_time 
    FROM shows  
    WHERE date = '{$row['date']}' AND show_id <> $id";
    $result = mysqli_query($conn, $sql);
    $arr = [];
    while ($row2 = mysqli_fetch_assoc($result)) {
        $arr[] = date('H:i', strtotime($row2['show_time']));
    }
    $arr = count($arr) > 0 ? array_values(array_diff(SHOWTIME, $arr)) : SHOWTIME;

} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head><title>404 Page</title>
    <body style='test-align: center;'>Page Not Found!</body>
    </html>";
    die();
}

$movies = getMoviesNameId();

$minDate = ($row['release_date'] > date("Y-m-d")) ? $row['release_date'] : date("Y-m-d");
?>

<body>
    <?php navbar(); ?>
    <main>
        <section style="max-width: 900px; margin-inline: auto; margin-bottom: 10px;">
            <h1>Update Show</h1>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="input-control">
                    <label for="movieID">Movie Name:</label>
                    <select name="movieID" id="movieID" required>
                        <?php foreach ($movies as $movie): ?>
                            <option value="<?= $movie['movie_id'] ?>" <?= ($movie['movie_id'] == $row['movie_id']) ? "selected" : "" ?>>
                                <?= $movie['movie_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-control">
                    <label for="date">Date:</label>
                    <input type="date" min="<?= $minDate ?>" value="<?= $row['date'] ?>" max="<?= date('Y-m-d', strtotime(date("Y-m-d") . ' + 10 days')) ?>" name="date" id="date">
                </div>
                <div class="input-control">
                    <label for="time">Time</label>
                    <select name="time" id="time">
                        <option value="">Select time</option>
                        <?php foreach ($arr as $showTime): ?>
                            <?php
                            $now = date('Y-m-d H:i:s');
                            $customDate = date_format(date_create($row['date'] . $showTime . ":00"), 'Y-m-d H:i:s');
                            if ($now < $customDate):
                                ?>
                                <option value="<?= $showTime ?>" <?= ($showTime . ":00" == $row['show_time']) ? "selected" : "" ?>>
                                    <?= $showTime ?>
                                </option>
                            <?php endif;
                        endforeach; ?>
                    </select>
                </div>
                <input type="submit" value="Update Show">
            </form>
        </section>
    </main>
    <?php footer(moduleList: $moduleList); ?>
</body>

</html>