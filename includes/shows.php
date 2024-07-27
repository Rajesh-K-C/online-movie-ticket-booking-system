<section class="shows">
    <h3>Shows</h3>
    <?php
    $sql = "SELECT show_id, date, show_time, movie_name  
    FROM shows s 
    INNER JOIN movies m ON s.movie_id = m.movie_id 
    ORDER BY s.date DESC, s.show_time DESC";
    $result = mysqli_query($conn, $sql);
    date_default_timezone_set('Asia/Kathmandu');

    function isBooked($conn, $id)
    {
        $result = mysqli_query($conn, "SELECT ticket_id FROM tickets WHERE show_id = $id");
        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
    ?>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movie Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $disabled = isBooked($conn, $row['show_id']) ? "disabled" : "";

                        ?>
                        <tr>
                            <td>
                                <?= $row['show_id'] ?>
                            </td>
                            <td>
                                <?= $row['movie_name'] ?>
                            </td>
                            <td>
                                <?= $row['date'] ?>
                            </td>
                            <td>
                                <?= getShowTime($row['show_time']) ?>
                            </td>
                            <td>
                                <a href="update-show.php?id=<?= $row['show_id'] ?>">
                                    <button <?= $disabled ?> type="button" class="btn"> Update </button>
                                </a>
                                <button <?= $disabled ?> type="button" class="btn" data-showid="<?= $row['show_id'] ?>"
                                    data-showname="<?= $row['movie_name'] ?>" onclick="deleteShow(this);">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Movies not Found!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    function deleteShow(element) {
        const id = element.dataset.showid;
        const name = element.dataset.showname;

        if (confirm("Are you sure you want to delete " + name + "'s show ?")) {
            location.href = `delete-show.php?id=${id}`;
        }
    }
</script>