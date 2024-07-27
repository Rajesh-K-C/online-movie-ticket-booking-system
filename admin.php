<?php
session_start();
if (isset ($_SESSION['role'])) {
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
$cssList[] = "login.css";
$moduleList[] = "adminLogin.js";
head(title: "Login", cssList: $cssList);
?>

<body>
    <?php navbar(); ?>
    <main class="login-page">
        <section class="forms">
            <h3>Admin Login</h3>
            <div id="message"></div>
            <?php loginForm("#"); ?>
        </section>

    </main>
    <?php footer(jsList: $jsList, moduleList: $moduleList); ?>
</body>

</html>