export function validateFirstName(firstName, error = null) {
    const rex = /^[a-zA-Z]{2,30}$/;
    if (firstName.length < 2) {
        displayErrorMessage(error, "First name contains at least two letters");
        return false;
    }
    if (firstName == "") {
        displayErrorMessage(error, "Please enter your first name");
        return false;
    }
    if (!rex.test(firstName)) {
        displayErrorMessage(error, "First name only contains letters");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

export function validateLastName(lastName, error = null) {
    const rex = /^[a-zA-Z]{2,30}$/;
    if (lastName.length < 2) {
        displayErrorMessage(error, "Last name contains at least two letters");
        return false;
    }
    if (lastName == "") {
        displayErrorMessage(error, "Please enter your last name");
        return false;
    }
    if (!rex.test(lastName)) {
        displayErrorMessage(error, "Last name only contains letters");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

export function validateEmail(email, error = null) {
    const rex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email == "") {
        displayErrorMessage(error, "Please enter your email");
        return false;
    }
    if (!rex.test(email)) {
        displayErrorMessage(error, "Please enter a valid email");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

export function validatePhone(phone, error = null) {
    const rex = /^(9){1}(8|7){1}\d{8}$/;
    if (phone == "") {
        displayErrorMessage(error, "Please enter your phone number");
        return false;
    }
    if (!rex.test(phone)) {
        displayErrorMessage(error, "Please enter a valid phone number");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

export function validatePassword(password, error = null) {
    const rex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/;
    if (password == "") {
        displayErrorMessage(error, "Please enter your password");
        return false;
    }
    if (!rex.test(password)) {
        displayErrorMessage(error, "Password must have 1 uppercase, 1 lowercase, 1 digit, and be 8-16 characters long.");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

export function validateConfirmPassword(password, confirmPassword, error = null) {
    if (confirmPassword !== password) {
        displayErrorMessage(error, "Confirm Password does not match");
        return false;
    }
    displayErrorMessage(error, "");
    return true;
}

function displayErrorMessage(error, message) {
    if (error) {
        error.textContent = message;
    }
}