
import Query from "./query.js";

const message = Query("#message");

export default async function Login(form, loginUrl) {
    form.preventDefault();
    form = form.target;
    const email = form.email.value.trim();
    const password = form.password.value;

    if (email !== "" && password.trim() !== "") {
        const data = {
            "email": email,
            "password": password
        };

        try {
            const response = await fetch(loginUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
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
                message.textContent = responseData.message;
                message.style.display = "block";
            }
        } catch (error) {
            console.error('There was a problem with the form submission:', error);
        }
    } else {
        message.textContent = "Email and password both required";
        message.style.display = "block";
    }
}