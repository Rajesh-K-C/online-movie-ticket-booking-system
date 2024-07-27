<?php     
date_default_timezone_set('Asia/Kathmandu');
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['email']}'"));
?>
<section class="profile">
    <h3>User profile</h3>
    <div>
        <div>
            <div>
                <b>Name:</b> <span class="name"><?= $user['first_name'] ?> <?= $user['last_name'] ?></span>
            </div>
            <div>
                <b>Email:</b> <span class="email"><?= $user['email'] ?></span>
            </div>
            <div>
                <b>Phone:</b> <span class="phone"><?= $user['phone'] ?></span>
            </div>
        </div>
        <div>
            <button type="button" class="btn" id="edit">Edit Profile</button>
            <button type="button" class="btn" id="change">Change Password</button>
        </div>
    </div>
</section>
<section class="tickets">
    <?php
    $email = $_SESSION['email'];
    $sql = "SELECT COUNT(t.payment_id) as no_of_tickets, t.payment_id, m.movie_name, CONCAT(s.date,' ', s.show_time) as date_time, t.ticket_status 
    FROM tickets t 
    INNER JOIN shows s ON t.show_id=s.show_id 
    INNER JOIN movies m ON s.movie_id = m.movie_id 
    INNER JOIN users u ON u.user_id = t.user_id 
    WHERE u.email='$email'
    GROUP BY t.payment_id ORDER BY s.date DESC, s.show_time DESC";
    $result = mysqli_query($conn, $sql);
    ?>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>No. of tickets</th>
                    <th>Movie Name</th>
                    <th>Date Time</th>
                    <th>Ticket Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
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
                                <?= $row['ticket_status'] ?>
                            </td>
                            <td>
                                <?php if ($row['ticket_status'] == "Booked" || $row['ticket_status'] == "Checkin"):
                                    $disabled = "";
                                    $n = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tickets WHERE payment_id='{$row['payment_id']}' AND ticket_status<>'Booked'"));
                                    if ($n > 0 || strtotime($row['date_time']) < time() + 60 * 10) {
                                        $disabled = 'disabled';
                                    }
                                    $n = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tickets WHERE payment_id='{$row['payment_id']}' AND ticket_status<>'Checkin'"));
                                    ?>
                                    <button type="button" <?= ($n > 0) ? "" : "disabled" ?>
                                        onclick="viewTickets('<?= $row['payment_id'] ?>');" class="btn">View</button>
                                    <button type="button" onclick="cancelTickets('<?= $row['payment_id'] ?>');" class="btn"
                                        <?= $disabled ?>>Cancel</button>
                                <?php else: ?>
                                    <button type="button" disabled class="btn">View</button>
                                    <button type="button" disabled class="btn">Cancel</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Ticket not Found!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<dialog id="editProfile" class="forms">
    <span class="closeBtn" id="editClose" title="Close">&times;</span>
    <form action="#" method="post" id="updateForm">
        <div class="input-control">
            <label for="fName">First Name </label>
            <input type="text" name="fName" id="fName" placeholder="Enter your first name">
            <span class="err" id="fnErr"></span>
        </div>
        <div class="input-control">
            <label for="lName">Last Name </label>
            <input type="text" name="lName" id="lName" placeholder="Enter your last name">
            <span class="err" id="lnErr"></span>
        </div>
        <div class="input-control">
            <label for="email">Email </label>
            <input type="email" name="email" id="email" autocomplete="email" placeholder="Enter your email">
            <span class="err" id="emailErr"></span>
        </div>
        <div class="input-control">
            <label for="phone">Phone </label>
            <input type="tel" name="phone" id="phone" autocomplete="phone" placeholder="Enter your phone number">
            <span class="err" id="phoneErr"></span>
        </div>
        <div class="input-control">
            <div>
                <input type="submit" name="update" value="Update">
            </div>
        </div>
    </form>
</dialog>
<dialog id="changePassword" class="forms">
    <span class="closeBtn" id="changeClose" title="Close">&times;</span>
    <form action="#" method="post" id="changeForm">
        <div class="input-control">
            <label for="op">Old Password</label>
            <input type="password" name="op" id="op" placeholder="Enter your password">
        </div>
        <div class="input-control">
            <label for="np">New Password </label>
            <input type="password" name="np" id="np" placeholder="Enter your new password">
            <span class="err" id="npErr"></span>
        </div>
        <div class="input-control">
            <label for="cp">Confirm New Password </label>
            <input type="password" name="cp" id="cp" placeholder="Confirm your new password">
            <span class="err" id="cpErr"></span>
        </div>
        <div class="input-control">
            <div>
                <input type="submit" name="change" value="Change">
            </div>
        </div>
    </form>
</dialog>
<script>
    function viewTickets(id) {
        location.href = "./tickets?id=" + id;
    }
    function cancelTickets(id) {
        if (confirm("Use your registered phone number as eSewa id")) {
            location.href = "./cancel?id=" + id + "&default=1";
        } else {
            const num = prompt("Enter your esewa id.").trim();
            if (/^9(8|7)[\d]{8}$/.test(num)) {
                location.href = "./cancel?id=" + id + "&num=" + num;
            } else {
                alert("Please enter a valid esewa id.");
            }
        }
    }
</script>