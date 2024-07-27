<?php
function adminNav(): void
{
    ?>
    <div class="adminNav">
        <a href="add-movie.php"><button type="button" class="btn">Add Movie</button></a>
        <a href="add-show.php"><button type="button" class="btn">Add Show</button></a>
        <a href="dashboard.php?page=movies"><button type="button" class="btn">Movies</button></a>
        <a href="dashboard.php?page=shows"><button type="button" class="btn">Shows</button></a>
        <a href="dashboard.php?page=cancelled"><button type="button" class="btn">Cancelled Ticket</button></a>
        <a href="dashboard.php?page=check-in"><button type="button" class="btn">Check in</button></a>
    </div>
    <?php
}