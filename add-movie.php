<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("location:./");
    die();
}

include_once "includes/functions.php";
include_once "includes/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = test_input($_POST['name']);
    $description = test_input($_POST['description']);
    $language = test_input($_POST['language']);
    $release = test_input($_POST['release']);
    $duration = test_input($_POST['duration']);

    $isMovieNameExist = mysqli_num_rows(mysqli_query($conn, "SELECT movie_id FROM movies WHERE movie_name = '$name'")) > 0;

    if ($isMovieNameExist) {
        $_SESSION["MSG"] = ["error", "Movie name already exists"];
    } elseif ($name && $description && $language && $release && $duration) {
        $targetDir = "assets/images/movies/";
        $fileName = basename($_FILES["image"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = time() . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;
        $extensions = ["jpeg", "jpg", "png"];

        if (!in_array($fileType, $extensions)) {
            $_SESSION["MSG"] = ["error", "extension not allowed, please choose a JPEG, JPG or PNG file."];

        } elseif (!empty($_FILES["image"]["name"])) {
            $sql = "INSERT INTO movies (movie_name, description, language, release_date, duration, image) VALUES ('$name', '$description', '$language', '$release',$duration, '$fileName')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $res = move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $targetFilePath
                );
            }
            $_SESSION["MSG"] = ["success", "The movie $name has been added"];
            header("location: dashboard.php");
            die();
        }
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
$moduleList[] = "add-movie";
head(title: "Add Movie", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <?php displayMessage(); ?>
    <main>
        <section style="max-width: 900px; margin-inline: auto; margin-bottom: 10px;">
            <h1>Add Movie</h1>
            <form action="add-movie.php" method="post" enctype="multipart/form-data">
                <div class="input-control">
                    <label for="name">Movie Name:</label>
                    <input type="text" name="name" id="name" placeholder="Enter Movie Name">
                    <span class="err nameErr"></span>
                </div>
                <div class="input-control">
                    <label for="description">Description:</label>
                    <textarea type="text" name="description" id="description"
                        placeholder="Enter Movie Description"></textarea>
                    <span class="err descriptionErr"></span>
                </div>
                <div class="input-control">
                    <label for="language">Select Movie Language:</label>
                    <select name="language" id="language">
                        <option value="">select language</option>
                        <?php foreach (LANGUAGES as $language): ?>
                            <option value="<?= $language ?>">
                                <?= $language ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="err languageErr"></span>
                </div>
                <div class="input-control">
                    <label for="release">Release Date:</label>
                    <input type="date" min="<?= date("Y-m-d") ?>"
                        max="<?= date('Y-m-d', strtotime(date("Y-m-d") . ' + 30 days')) ?>" name="release" id="release">
                    <span class="err releaseErr"></span>
                </div>
                <div class="input-control">
                    <label for="duration">Duration(mins):</label>
                    <input type="number" name="duration" id="duration" placeholder="Enter duration in minutes">
                    <span class="err durationErr"></span>
                </div>
                <div class="input-control">
                    <label for="image">Image:</label>
                    <input type="file" name="image" accept="image/jpeg, image/jpg, image/png" id="image">
                    <span class="err imageErr"></span>
                </div>
                <input type="submit" value="Add">
            </form>
        </section>
    </main>
    <?php footer(moduleList: $moduleList); ?>

</body>

</html>