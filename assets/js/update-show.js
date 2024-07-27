import Query from './query';

const movie = Query("#movieID");
const date = Query("#date");
const time = Query("#time");

const url = new URL(window.location.href);

const params = new URLSearchParams(url.search);

const id = params.get('id');

if (id !== null) {
    movie.addEventListener("change", () => {
        date.value = "";
        time.value = "";
    });

    date.addEventListener("change", async (e) => {
        let response = await fetch("requests/get-available-show-time.php?date=" + date.value + "&show=" + id);
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
    });
}