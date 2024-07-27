import Query from './query';

const day_cards = Query(".day-card .date", true);
const show_times = Query(".show-timing");
const seats = Query(".all-seats");
const totalAmount = Query(".amount");
const seatCount = Query(".count");
const btn = Query(".price button");
let amount = 0;
let count = 0;
let bookedSeats;
let timerInterval;

const ticketPrice = Number(Query(".movie-booking").dataset.price);

const rows = ["H", "G", "F", "E", "D", "C", "B", "A"];

const movieId = day_cards[0].dataset.movieid;

day_cards.forEach((card) => {
    card.addEventListener("click", (e) => {
        if (!card.classList.contains("active")) {
            displayShowTimes(card.dataset.date, movieId);
            day_cards.forEach((e) => {
                e.classList.remove("active");
            });
            card.classList.add("active");
        }
    });
});

displayShowTimes(day_cards[0].dataset.date);

async function displayShowTimes(date) {
    let response = await fetch(`requests/get-show-times.php?date=${date}&movie_id=${movieId}`);
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    if (responseData.success) {
        const showTimes = responseData.data;
        let isActive = true;
        let str = "";
        showTimes.forEach((time) => {
            str += `<li class="time ${(isActive) ? 'active-time' : ''}" data-show="${time[0]}">${time[1]}</li>`;
            isActive = false;
        });
        show_times.innerHTML = str;
        const times = Query(".time", true);
        times.forEach((time) => {
            time.addEventListener("click", (e) => {
                if (!time.classList.contains("active-time")) {
                    times.forEach((e) => {
                        e.classList.remove("active-time");
                    });
                    time.classList.add("active-time");
                    displaySeats();
                }
            });
        });
        displaySeats();
    }
}

async function displaySeats() {
    const showId = Query(".active-time").dataset.show;

    let response = await fetch(`requests/get-booked-seats.php?showId=${showId}`);
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    if (responseData.success) {
        bookedSeats = responseData.data;
        let str = "";

        for (let row of rows) {
            str += `<span class="row">${row}</span>`;
            for (let j = 1; j < 10; j++) {
                const seatNo = (row + j);
                const seatId = (row + j).toLowerCase();
                let booked = (bookedSeats.includes(seatNo)) ? "disabled" : "";
                str += `<input class="seat-no" type="checkbox" name="tickets[]" value="${seatNo}" id="${seatId}" ${booked}><label for="${seatId}" class="seat"> ${seatNo}</label>`;
            }
        }
        str += `<input type="hidden" name="show" value="${showId}">`;
        seats.innerHTML = str;

        totalAmount.textContent = 0;
        seatCount.textContent = 0;
        amount = 0;
        count = 0;

        btn.setAttribute("disabled", "");

        Query(".seat-no", true).forEach((ticket) => {
            ticket.addEventListener("change", () => {
                if (ticket.checked) {
                    count += 1;
                    amount += ticketPrice;
                } else {
                    count -= 1;
                    amount -= ticketPrice;
                }
                totalAmount.textContent = amount;
                seatCount.textContent = count;
                if (amount === 0) {
                    btn.setAttribute("disabled", "");
                } else {
                    btn.removeAttribute("disabled");
                }
            });
        });
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        timerInterval = setInterval(updateSeats, 1000);
    }
}

async function updateSeats() {
    const showId = Query(".active-time").dataset.show;
    let response = await fetch(`requests/get-booked-seats.php?showId=${showId}`);
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    if (responseData.success) {
        const newData = responseData.data;

        updateChanges(newData);
    }
}
function updateChanges(newData) {

    Query(".seat-no", true).forEach((ticket) => {
        const seatNo = ticket.value;

        if (newData.includes(seatNo)) {

            if (ticket.checked) {
                ticket.checked = false;
                count -= 1;
                amount -= ticketPrice;

                totalAmount.textContent = amount;
                seatCount.textContent = count;
                if (amount === 0) {
                    btn.setAttribute("disabled", "");
                } else {
                    btn.removeAttribute("disabled");
                }
            }
            ticket.setAttribute("disabled", "");
        } else {
            ticket.removeAttribute("disabled");
        }
    });
}

btn.addEventListener("click", bookTickets);

async function bookTickets() {

    const response = await fetch('./requests/check-login');

    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const responseData = await response.json();

    if (responseData.success) {
        if (responseData.data === "Success") {
            Query("form").submit();
        }
    } else if (responseData.data === "Failure") {
        Query("dialog").showModal();
        document.body.style.overflow = "hidden";
    } else {
        alert(responseData.data);
    }
}

Query("dialog .closeBtn").addEventListener("click", () => {
    Query("dialog").close();
    document.body.style.overflow = "auto";
    Query("dialog form", true).forEach((form) => form.reset());
});