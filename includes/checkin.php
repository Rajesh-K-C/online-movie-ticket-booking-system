<section class="checkin">
    <div class="flex justify-content-between">
        <h3>Checkin</h3>
        <form action="#" method="get">
            <input type="search" class="my-10 size-16 p-5" name="query" placeholder="Search Ticket id...">
        </form>
    </div>
    <?php
    $sql = "SELECT t.ticket_id, m.movie_name, t.seat_no, CONCAT(s.date,' ', s.show_time) as date_time, t.ticket_status 
    FROM tickets t 
    INNER JOIN shows s ON t.show_id=s.show_id 
    INNER JOIN movies m ON s.movie_id = m.movie_id 
    WHERE DATE_ADD(CONCAT(s.date,' ', s.show_time), INTERVAL 1 Hour) > NOW()
    ORDER BY s.date DESC, s.show_time DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Movie Name</th>
                    <th>Seat No.</th>
                    <th>Date Time</th>
                    <th>Ticket Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <?= $row['ticket_id'] ?>
                            </td>
                            <td>
                                <?= $row['movie_name'] ?>
                            </td>
                            <td>
                                <?= $row['seat_no'] ?>
                            </td>
                            <td>
                                <?= $row['date_time'] ?>
                            </td>
                            <td>
                                <?= $row['ticket_status'] ?>
                            </td>
                            <td>
                                <button type="button" class="btn" <?= $row['ticket_status'] != "Booked" ? "disabled" : "" ?>
                                    onclick="checkin(<?= $row['ticket_id'] ?>)">Check in</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Ticket not Found!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    function checkin(id) {
        location.href = `checkin.php?id=${id}`;
    }
</script>

<script type="module">
    import Query from './assets/js/query';

    Query('form').addEventListener("submit", async (e) => {
        e.preventDefault();
        const form = e.target;
        const q = form.query.value.trim();

        const response = await fetch("requests/search-checkin.php?query=" + q);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const responseData = await response.json();

        if (responseData.success) {
            const data = responseData.data;
            let str = "";
            const now = new Date();
            now.setHours(now.getHours() + 1);
            if (data !== "No Result Found!") {
                data.forEach(element => {
                    const date = new Date(element.date_time);
                    str += `<tr><td>${element.ticket_id}</td>
                        <td>${element.movie_name}</td>
                        <td>${element.seat_no}</td>
                        <td>${element.date_time}</td>
                        <td>${element.ticket_status}</td>
                        <td>
                            <button type="button" onclick="checkin(${element.ticket_id})" class="btn" ${element.ticket_status != "Booked" || now > date ? "disabled" : ""}>Check in</button>
                        </td>`;
                });
            } else {
                str = ` <tr>
                    <td colspan="6" style="text-align: center;">Search result not found!</td>
                </tr>`;
            }
            Query('tbody').innerHTML = str;
            form.query.value = "";
        }
    });

</script>