<?php
function head($title, $cssList = [])
{
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?= $title ?>
        </title>
            <link rel="stylesheet" href="assets/css/style.css">
            <link rel="stylesheet" href="assets/css/nav.css">
            <link rel="stylesheet" href="assets/css/footer.css">
        <?php foreach ($cssList as $css): ?>
            <link rel="stylesheet" href="assets/css/<?= $css ?>">
        <?php endforeach; ?>
    </head>
    <?php
}
?>