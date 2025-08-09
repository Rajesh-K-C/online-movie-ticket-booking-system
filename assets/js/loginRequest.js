
import Query from "./query.js";

const message = Query("#message");

export default async function Login(form, loginUrl) {
    form.preventDefault();
    form = form.target;
    const email = form.email.value.trim();
    const password = form.password.value;
    let btn = form.login;

    const setBtn = (loading = false) => {
        if (!loading) {
            btn.value = "Login";
            btn.style.cursor = "pointer"
        } else {
            btn.value = "Loading...";
            btn.style.cursor = "not-allowed"
        }
    }

    const setMessage = (msg) => {
        setTimeout(() => {
            message.textContent = "";
            message.style.display = "none";
        }, 3000);
        message.textContent = msg;
        message.style.display = "block";
        setBtn()
    }

    if (email !== "" && password.trim() !== "") {
        const data = {
            "email": email,
            "password": password
        };

        try {
            setBtn(true)
            const response = await fetch(loginUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                setBtn(false)
                throw new Error('Network response was not ok');
            }

            const responseData = await response.json();

            if (responseData.success) {
                if (location.pathname == "/admin" || location.pathname == "/admin.php") {
                    window.location.href = "./dashboard";
                } else if (location.pathname == "/booking" || location.pathname == "/booking.php") {
                    Query('form').submit();
                } else {
                    window.location.href = "./";
                }

            } else {
                setMessage(responseData.message)
            }
        } catch (error) {
            console.error('There was a problem with the form submission:', error);
            setMessage("There was a problem with the form submission")
        }
    } else {
        setMessage("Email and password both required")
    }
}