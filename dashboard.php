<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("location:./");
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
include_once "includes/connection.php";
include_once "includes/functions.php";

$cssList[] = "table.css";
if ($_SESSION['role'] == "admin") {
    $cssList[] = "card.css";
    $cssList[] = "adminNav.css";
    $title = "Admin Dashboard";
    $moduleList[] = "adminNav";
} else {
    $cssList[] = "userDashboard.css";
    $cssList[] = "login.css";
    $title = "User Dashboard";
    $moduleList[] = "userDashboard";
}
head(title: $title, cssList: $cssList);
?>

<body>
    <?php navbar();
    if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
        include_once "includes/adminNav.php";
        adminNav();
    } ?>

    <main class="dashboard">
        <?php displayMessage(); ?>
        <?php if ($_SESSION['role'] == "admin"): ?>
            <?php include_once "includes/adminDashboard.php"; ?>
        <?php else: ?>
            <?php include_once "includes/userDashboard.php"; ?>
        <?php endif; ?>
    </main>
    <?php footer(moduleList: $moduleList); ?>
</body>

</html>