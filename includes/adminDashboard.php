<?php
if (!isset($_GET["page"]) || $_GET["page"] == "movies") {
    include_once "movies.php";
} elseif ($_GET["page"] == "shows") {
    $moduleList[] = "table";
    include_once "shows.php";
} elseif ($_GET["page"] == "cancelled") {
    $moduleList[] = "table";
    include_once "cancelled.php";
} else {
    $moduleList[] = "table";
    include_once "checkin.php";
}