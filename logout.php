<?php
session_start();
$role = $_SESSION["role"];
session_unset();
session_destroy();
if ($role == "admin") {
    header("location: admin");
    die();
} else {
    header("location: login");
    die();
}