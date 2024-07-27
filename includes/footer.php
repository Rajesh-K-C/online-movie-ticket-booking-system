<?php
function footer($jsList = [], $moduleList = [])
{
?>
    <footer>
        <p>Copyright &copy; <?= date("Y"); ?> Movie Ticket Booking</p>
    </footer>

    <script src="assets/js/nav.js"></script>
    <?php foreach ($jsList as $js) : ?>
        <script src="assets/js/<?= $js ?>"></script>
    <?php endforeach; ?>
    <?php foreach ($moduleList as $js) : ?>
        <script type="module" src="assets/js/<?= $js ?>"></script>
    <?php endforeach; ?>
<?php
}
?>