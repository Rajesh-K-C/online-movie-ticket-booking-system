import Query from './query';
import { padZero, convertTimeFormat } from './functions';

const movieID = Query("#movieID");
const date = Query("#date");
const time = Query("#time");

movieID.addEventListener("change", async (e) => {
    let response = await fetch("requests/get-release-date.php?id=" + movieID.value);
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    if (responseData.success) {
        let releaseDate = responseData.data;

        releaseDate = new Date(`${releaseDate}`);
        let toDay = new Date();
        let newDate = new Date(`${toDay.getFullYear()}-${toDay.getMonth() + 1}-${toDay.getDate()}`);

        let dateStr;
        if (releaseDate > newDate) {
            dateStr = `${releaseDate.getFullYear()}-${padZero(releaseDate.getMonth() + 1)}-${padZero(releaseDate.getDate())}`;
        } else {
            dateStr = `${newDate.getFullYear()}-${padZero(newDate.getMonth() + 1)}-${padZero(newDate.getDate())}`;
        }
        date.setAttribute("min", dateStr);
        date.value = "";
        time.value = "";
    }
});

date.addEventListener("change", async (e) => {
    if (date) {
        let response = await fetch("requests/get-available-show-time.php?date=" + date.value);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const responseData = await response.json();
        if (responseData.success) {
            let releaseDate = responseData.data;
            let str = `<option value="">Select time</option>`;
            let now = new Date();
            let showDate = new Date(date.value);
            releaseDate.forEach(element => {
                showDate.setHours(element.split(":")[0], 0, 0, 0);
                if (now < showDate) {
                    str += `<option value="${element}">${element}</option>`;
                }
            });
            time.innerHTML = str;
            time.value = "";
        }
    }
});

Query('form').addEventListener('submit', (form) => {
    form.preventDefault();
    const nameErr = Query(".nameErr");
    const dateErr = Query(".dateErr");
    const timeErr = Query(".timeErr");

    if (movieID.value) {
        nameErr.textContent = "";
    } else {
        nameErr.textContent = "Please select a movie";
    }

    if (date.value) {
        dateErr.textContent = "";
    } else {
        dateErr.textContent = "Please select a date";
    }

    if (time.value) {
        timeErr.textContent = "";
    } else {
        timeErr.textContent = "Please select a time";
    }

    if (movieID.value && date.value && time.value) {
        form.target.submit();
    }
});