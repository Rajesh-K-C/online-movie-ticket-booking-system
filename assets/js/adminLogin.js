import Login from "./loginRequest";
import Query from "./query";

const loginForm = Query(".login-form");

const loginUrl = "./requests/adminLogin.php";

loginForm.addEventListener("submit", form => Login(form, loginUrl));