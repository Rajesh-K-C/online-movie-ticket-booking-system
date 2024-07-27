<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:./");
    die();
}

include_once "includes/functions.php";
include_once "includes/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) test_input($_POST['id']);
    $name = test_input($_POST['name']);
    $description = test_input($_POST['description']);
    $language = test_input($_POST['language']);
    $release = test_input($_POST['release']);
    $duration = (int) test_input($_POST['duration']);
    $oldImg = test_input($_POST['oldImg']);

    if ($name && $description && $language && $release && $duration && $oldImg && $id) {
        if (!empty($_FILES["image"]["name"])) {
            $targetDir = "assets/images/movies/";
            $fileName = basename($_FILES["image"]["name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = time() . "." . $fileType;
            $targetFilePath = $targetDir . $fileName;

            $sql = "UPDATE movies SET movie_name='$name', description='$description', language='$language', release_date='$release', duration=$duration, image='$fileName' WHERE movie_id='$id'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $res = move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $targetFilePath
                );
                if ($res) {
                    $filePath = "assets/images/movies/" . $oldImg;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        } else {
            $sql = "UPDATE movies SET movie_name='$name', description='$description', language='$language', release_date='$release', duration=$duration WHERE movie_id='$id'";
            $result = mysqli_query($conn, $sql);
        }
        $_SESSION["MSG"] = ["success", "The movie $name has been updated."];

        header("location: dashboard.php");
        die();
    }
}
if (isset($_GET['id'])) {
    $id = (int) test_input($_GET['id']);
    $sql = "SELECT * FROM movies WHERE movie_id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $result = mysqli_query($conn, "SELECT ticket_id FROM tickets t JOIN shows s ON t.show_id = s.show_id WHERE s.movie_id = $id");
    $ticket_booked = mysqli_num_rows($result) > 0 ? true : false;

    $result = mysqli_query($conn, "SELECT date FROM shows WHERE movie_id = $id order by date limit 1");
    $max_date = (mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['date'] : date('Y-m-d', strtotime(date("Y-m-d") . ' + 30 days'));

    $min_date = (date("Y-m-d") > $row['release_date']) ? $row['release_date'] : date("Y-m-d");

    $isDateChangeable = ($min_date < $max_date && date("Y-m-d") <= $row['release_date']) ? true : false;
} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head><title>404 Page</title>
    <body style='test-align: center;'>Page Not Found!</body>
    </html>";
    die();
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
$moduleList[] = "update-movie";
head(title: "Add Movie", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <main>
        <section style="max-width: 900px; margin-inline: auto; margin-bottom: 10px;">
            <h1>Update Movie</h1>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="oldImg" value="<?= $row['image'] ?>">
                <div class="input-control">
                    <label for="name">Movie Name:</label>
                    <?php if ($ticket_booked): ?>
                        <input type="hidden" value="<?= $row['movie_name'] ?>" name="name" id="name">
                        <span class="err nameErr display-none"></span>
                        <p><?= $row['movie_name'] ?></p>
                    <?php else: ?>
                        <input type="text" value="<?= $row['movie_name'] ?>" name="name" id="name"
                            placeholder="Enter Movie Name">
                        <span class="err nameErr"></span>
                    <?php endif; ?>
                </div>
                <div class="input-control">
                    <label for="description">Description:</label>
                    <textarea type="text" name="description" id="description"
                        placeholder="Enter Movie Description"><?= $row['description'] ?></textarea>
                    <span class="err descriptionErr"></span>
                </div>
                <div class="input-control">
                    <label for="language">Movie Language:</label>
                    <?php if ($ticket_booked): ?>
                            <input type="hidden" value="<?= $row['language'] ?>" name="language" id="language">
                            <span class="err languageErr display-none"></span>
                            <p><?= $row['language'] ?></p>
                    <?php else: ?>
                    <select name="language" id="language">
                        <?php foreach (LANGUAGES as $language): ?>
                            <option value="<?= $language ?>" <?= ($language == $row['language']) ? "selected" : "" ?>>
                                <?= $language ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="err languageErr"></span>
                    <?php endif; ?>
                </div>
                <div class="input-control">
                    <label for="release">Release Date:</label>
                    <?php if ($isDateChangeable): ?>
                        <input type="date" value="<?= $row['release_date']; ?>" min="<?= $min_date; ?>" max="<?= $max_date; ?>"
                            name="release" id="release">
                        <span class="err releaseErr"></span>
                    <?php else: ?>
                        <input type="hidden" value="<?= $row['release_date']; ?>" name="release" id="release">
                        <span class="err releaseErr display-none"></span>
                        <p><?= $row['release_date']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-control">
                    <label for="duration">Duration(m):</label>
                    <input type="number" value="<?= $row['duration']; ?>" name="duration" id="duration"
                        placeholder="Enter duration in minutes">
                    <span class="err durationErr"></span>
                </div>
                <div class="input-control">
                    <label for="image">Image:</label>
                    <input type="file" name="image" accept="image/jpeg, image/jpg, image/png" id="image">
                    <span class="err imageErr"></span>
                </div>
                <input type="submit" value="Update">
            </form>
        </section>
    </main>
    <?php footer(moduleList: $moduleList); ?>
</body>

</html>