<?php
function setSession($user, $email): void
{
    $_SESSION['role'] = $user;
    $_SESSION['email'] = $email;
}
function test_input($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getHoursMinutes($mins): string
{
    $hours = floor($mins / 60);
    $minutes = ($mins % 60);
    if ($minutes == 1) {
        $minutes = sprintf("%02d", $minutes) . " minute";
    } elseif ($minutes == 0) {
        $minutes = "";
    } else {
        $minutes = sprintf("%02d", $minutes) . " minutes";
    }

    if ($hours == 1) {
        $hours = "$hours hour ";
    } else {
        $hours = "$hours hours ";
    }
    return $hours . $minutes;
}

function displayMessage()
{
    if (isset ($_SESSION["MSG"])) {
        echo "<div class='" . $_SESSION['MSG'][0] . " msg'>" . $_SESSION['MSG'][1] . '</div>';
        unset($_SESSION['MSG']);
    }
}

function getMoviesNameId()
{
    $sql = "SELECT movie_id, movie_name FROM movies ORDER BY movie_id DESC LIMIT 20";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    $movies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
    return $movies;
}

function getShowTime($datString): string
{
    $timestamp = strtotime($datString);
    return date('H:i', $timestamp);
}
