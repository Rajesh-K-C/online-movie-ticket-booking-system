<?php
session_start();
if (isset($_SESSION['role'])) {
    header("location: ./");
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";
include_once "includes/loginForm.php";

$moduleList[] = "login.js";
$cssList[] = "login.css";

head(title: "Login", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <main class="login-page">
        <?php include_once "includes/loginForms.php" ?>
    </main>
    <?php footer(moduleList: $moduleList); ?>
</body>

</html>