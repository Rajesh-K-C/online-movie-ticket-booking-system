<h3>Movies</h3>
<section class="movie-section">
    <?php
    $sql = "SELECT movie_id, movie_name, language, release_date, duration, image FROM movies ORDER BY release_date DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <article class="movie-card">
                <a href="movie.php?id=<?= $row['movie_id'] ?>">
                    <img src="assets/images/movies/<?= $row['image'] ?>" title="<?= $row['movie_name'] ?>"
                        alt="<?= $row['movie_name'] ?>">
                </a>
                <div>
                    <h4>
                        <?= strtoupper($row['movie_name']) ?>
                    </h4>
                    <p class="run-time">
                        Releasing on:
                        <?= $row['release_date'] ?>
                    </p>
                    <p class="run-time">Runtime:
                        <?= getHoursMinutes($row['duration']) ?>
                    </p>
                    <button type="button" class="btn" data-movieid="<?= $row['movie_id'] ?>" onclick="updateMovie(this);">
                        Update </button>
                    <button type="button" class="btn" data-movieid="<?= $row['movie_id'] ?>"
                        data-moviename="<?= $row['movie_name'] ?>" onclick="deleteMovie(this);">Delete</button>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align: center;">Movies not Found!</div>
    <?php endif; ?>
</section>
<script>
    function deleteMovie(element) {
        const id = element.dataset.movieid;
        const name = element.dataset.moviename;

        if (confirm("Are you sure you want to delete '" + name + "' movie ?")) {
            location.href = `delete-movie.php?id=${id}&name=${name}`;
        }
    }
    function updateMovie(element) {
        const id = element.dataset.movieid;
        location.href = `update-movie.php?id=${id}`;
    }
</script>