import { validateFirstName, validateLastName, validateEmail, validatePhone, validatePassword, validateConfirmPassword } from './validation';
import Query from './query';
import Login from './loginRequest';

const login = Query("#login");
const signUp = Query("#signUp");
const loginForm = Query(".login-form");
const signUpForm = Query(".signup-form");
const message = Query("#message");

const loginUrl = './requests/login.php';

login.addEventListener("click", () => {
    if (!login.classList.contains("selected")) {
        toggleForm();
    }
});

signUp.addEventListener("click", () => {
    if (!signUp.classList.contains("selected")) {
        toggleForm();
    }
});

function toggleForm() {
    message.style.display = "none";
    login.classList.toggle("selected");
    signUp.classList.toggle("selected");
    loginForm.classList.toggle("display-none");
    signUpForm.classList.toggle("display-none");
}

signUpForm.addEventListener("submit", async (form) => {
    form.preventDefault();
    form = form.target;
    const fName = form.fName.value.trim();
    const lName = form.lName.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const password = form.password.value;
    const cPassword = form.cPassword.value;

    const isFirstNameValid = validateFirstName(fName, Query("#fnErr"));
    const isLastNameValid = validateLastName(lName, Query("#lnErr"));
    const isEmailValid = validateEmail(email, Query("#emailErr"));
    const isPhoneValid = validatePhone(phone, Query("#phoneErr"));
    const isPasswordValid = validatePassword(password, Query("#pErr"));
    const isConfirmPasswordValid = validateConfirmPassword(password, cPassword, Query("#cpErr"));

    let btn = form.login;

    const setBtn = (loading = false) => {
        if (!loading) {
            btn.value = "Register";
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

    if (isFirstNameValid && isLastNameValid && isEmailValid && isPhoneValid && isPasswordValid && isConfirmPasswordValid) {

        const data = {
            "fName": fName,
            "lName": lName,
            "email": email,
            "phone": phone,
            "password": password
        };

        try {
            const response = await fetch('./requests/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const responseData = await response.json();

            if (responseData.success) {
                if (location.pathname == "/booking") {
                    Query('form').submit();
                } else {
                    window.location.href = "./";
                }
            } else {
                setMessage(responseData.message)
            }
        } catch (error) {
            console.error('There was a problem with the form submission:', error);
            setMessage('There was a problem with the form submission')
        }
    } else {
        message.style.display = "none";
    }
});

loginForm.addEventListener("submit", form => Login(form, loginUrl));