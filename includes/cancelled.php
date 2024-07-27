<section class="cancelled">
    <?php displayMessage(); ?>
    <h3>Cancelled Tickets</h3>
    <?php
    $sql = "SELECT  COUNT(t.payment_id) as no_of_tickets,p.amount, t.payment_id, m.movie_name, r.esewa_id, CONCAT(s.date,' ', s.show_time) as date_time
    FROM tickets t 
    INNER JOIN shows s ON t.show_id=s.show_id 
    INNER JOIN movies m ON s.movie_id = m.movie_id 
    INNER JOIN refunds r ON t.payment_id=r.payment_id
    INNER JOIN payment p ON t.payment_id=p.payment_id
    WHERE t.ticket_status='cancelled' 
    GROUP BY t.payment_id 
    ORDER BY s.date DESC, s.show_time DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>No. of tickets</th>
                    <th>Movie Name</th>
                    <th>Date Time</th>
                    <th>eSewa id</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $payment_id = $row['payment_id'];
                        $esewa_id = $row['esewa_id'];
                        ?>
                        <tr>
                            <td>
                                <?= $row['no_of_tickets'] ?>
                            </td>
                            <td>
                                <?= $row['movie_name'] ?>
                            </td>
                            <td>
                                <?= $row['date_time'] ?>
                            </td>
                            <td>
                                <?= $esewa_id ?>
                            </td>
                            <td>
                                Rs. <?= $row['amount'] ?>
                            </td>
                            <td>
                                <button type="button" class="btn"
                                    onclick="refundProcess('<?= $esewa_id ?>', '<?= $payment_id ?>')">Refund</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Cancelled Ticket not Found!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    function refundProcess(eSewaId, payment_id) {
        if (confirm(`Are you sure you want to refund to the ${eSewaId} eSewa ID?`)) {
            // Redirect to the refund page
            location.href = `refund-process.php?id=${payment_id}`;
        }
    }
</script>