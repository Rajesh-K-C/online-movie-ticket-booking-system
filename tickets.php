<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "user" || !isset($_GET['id'])) {
  header("location: ./");
  die();
}
include_once "includes/connection.php";
include_once "includes/functions.php";

$id = test_input($_GET['id']);
$email = $_SESSION['email'];

$sql = "SELECT t.ticket_id, t.date_time as book_date_time, t.seat_no, s.date, s.show_time, m.movie_name, m.image
FROM tickets t 
INNER JOIN shows s ON s.show_id=t.show_id 
INNER JOIN movies m ON s.movie_id=m.movie_id 
INNER JOIN users u ON t.user_id=u.user_id
WHERE t.payment_id='$id' AND t.ticket_status='Booked' AND u.email='$email' 
LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
  $_SESSION["MSG"] = ["error", "Tickets not found."];
  header("location: dashboard");
  die();
}
$movie = mysqli_fetch_assoc($result);

$result = mysqli_query($conn, "SELECT ticket_id, seat_no FROM tickets WHERE payment_id='$id' AND ticket_status='Booked'");

$tids = [];
$snos = [];
while ($row = mysqli_fetch_assoc($result)) {
  $snos[] = $row['seat_no'];
  $tids[] = $row['ticket_id'];
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/footer.php";
include_once "includes/variables.php";
include_once "includes/loginForm.php";
include_once "includes/functions.php";

$cssList[] = "tickets.css";

head(title: "Tickets Download Page", cssList: $cssList);
?>

<body>
  <?php navbar(); ?>
  <main>
    <section class="tickets" data-tickets="<?php foreach ($tids as $tid)
      echo $tid . ' ' ?>" data-seats="<?php foreach ($snos as $sno)
      echo $sno . ' ' ?>" data-image="<?= $movie['image'] ?>" data-name="<?= $movie['movie_name'] ?>"
      data-date="<?= $movie['date'] ?>" data-time="<?= $movie['show_time'] ?>"></section>
  </main>
  <?php footer(); ?>
  <script>
    const ticketsElement = document.querySelector(".tickets");
    const ticketNo = ticketsElement.dataset.tickets.split(" ").filter((e) => e !== "");
    const seatNo = ticketsElement.dataset.seats.split(" ").filter((e) => e !== "");

    const movieName = ticketsElement.dataset.name;
    const date = ticketsElement.dataset.date;
    const time = ticketsElement.dataset.time;
    const movieImage = "assets/images/movies/" + ticketsElement.dataset.image;
    const img = document.createElement("img");
    img.src = "assets/images/movies/<?= $movie['image'] ?>";

    const height = 350;
    const width = 350;

    for (const i in seatNo) {
      drawTicket(seatNo[i], ticketNo[i]);
    }

    function drawTicket(seatNo, ticketId) {
      const canvas = document.createElement('canvas');
      canvas.height = height;
      canvas.width = width;

      const div = document.createElement("div");
      div.appendChild(canvas);
      const button = document.createElement("button");
      const parts = date.split("-");
      button.classList.add("btn");
      button.setAttribute("onclick", "downloadTicket(this, '" + parts.join('') + time.slice(":")[0] + seatNo + ticketId + ".png');");
      button.textContent = "Download";
      div.appendChild(button);
      ticketsElement.appendChild(div);
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, width, height);

      ctx.fillStyle = "white";
      ctx.fillRect(0, 0, width, height);

      ctx.textAlign = 'center';
      ctx.fillStyle = 'black';
      ctx.font = 'bold 24px Arial';
      ctx.fillText('Online Movie Ticket Booking', width / 2, 50);

      ctx.font = 'bold 18px Arial';
      wrapText(ctx, 'Movie: ' + movieName, width / 2, 100, width - 20, 18);


      ctx.font = '600 14px Arial';
      ctx.fillText('Date: ' + date, width / 2, 150);
      ctx.fillText('Time: ' + time.substr(0, 5), width / 2, 180);
      ctx.fillText('Seat: ' + seatNo, width / 2, 210);
      ctx.fillText('Ticket ID: ' + ticketId, width / 2, 240);
    }

    function downloadTicket(element, ticketName) {
      const canvas = element.parentNode.firstElementChild;
      const link = document.createElement('a');
      link.download = ticketName;
      link.href = canvas.toDataURL('image/png').replace("image/png", "image/octet-stream");
      link.click();
    }

    function wrapText(context, text, x, y, maxWidth, lineHeight) {
      var words = text.split(' ');
      var line = '';

      for (var n = 0; n < words.length; n++) {
        var testLine = line + words[n] + ' ';
        var metrics = context.measureText(testLine);
        var testWidth = metrics.width;
        if (testWidth > maxWidth && n > 0) {
          context.fillText(line, x, y);
          line = words[n] + ' ';
          y += lineHeight;
        }
        else {
          line = testLine;
        }
      }
      context.fillText(line, x, y);
    }
  </script>
</body>

</html>