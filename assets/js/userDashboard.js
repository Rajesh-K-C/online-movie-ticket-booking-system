import Query from './query';
import { validateFirstName, validateLastName, validateEmail, validatePhone, validatePassword, validateConfirmPassword } from './validation';
const edit = Query("#edit");
const change = Query("#change");
const editProfile = Query("#editProfile");
const changePassword = Query("#changePassword");
const editClose = Query("#editClose");
const changeClose = Query("#changeClose");
const updateForm = Query("#updateForm");
const changeForm = Query("#changeForm");
const fnErr = Query("#fnErr");
const lnErr = Query("#lnErr");
const emailErr = Query("#emailErr");
const phoneErr = Query("#phoneErr");
const npErr = Query("#npErr");
const cpErr = Query("#cpErr");

edit.addEventListener("click", () => {
    const name = Query('.name').textContent.split(" ");
    Query("#fName").value = name[0];
    Query("#lName").value = name[1];
    Query("#email").value = Query(".email").textContent;
    Query("#phone").value = Query(".phone").textContent;

    fnErr.innerHTML = "";
    lnErr.innerHTML = "";
    emailErr.innerHTML = "";
    phoneErr.innerHTML = "";

    editProfile.showModal();
    document.body.style.overflow = 'hidden';
    document.body.style.marginRight = '10px';
});

change.addEventListener("click", () => {
    npErr.innerHTML = "";
    cpErr.innerHTML = "";

    changePassword.showModal();
    document.body.style.overflow = 'hidden';
    document.body.style.marginRight = '10px';
});

editClose.addEventListener("click", () => {
    editProfile.close();
    document.body.style.overflow = 'auto';
    document.body.style.marginRight = '0px';
});

changeClose.addEventListener("click", () => {
    changePassword.close();
    document.body.style.overflow = 'auto';
    document.body.style.marginRight = '0px';
});

updateForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fName = Query('#fName').value.trim();
    const lName = Query('#lName').value.trim();
    const email = Query('#email').value.trim();
    const phone = Query('#phone').value.trim();

    const isFirstNameValid = validateFirstName(fName, fnErr);
    const isLastNameValid = validateLastName(lName, lnErr);
    const isEmailValid = validateEmail(email, emailErr);
    const isPhoneValid = validatePhone(phone, phoneErr);

    if (isFirstNameValid && isLastNameValid && isEmailValid && isPhoneValid) {
        const data = {
            "fName": fName,
            "lName": lName,
            "email": email,
            "phone": phone
        };

        const xhr = new XMLHttpRequest();
        xhr.open('POST', './requests/edit-profile.php');
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function () {
            if (xhr.status === 200) {
                const responseData = JSON.parse(xhr.responseText);
                if (responseData.success) {
                    document.querySelector('.name').textContent = fName + " " + lName;
                    document.querySelector('.email').textContent = email;
                    document.querySelector('.phone').textContent = phone;
                    editProfile.close();
                    document.body.style.overflow = 'auto';
                    document.body.style.marginRight = '0px';
                }
                alert(responseData.message);
            } else {
                console.error('Request failed. Status:', xhr.status);
            }
        };

        xhr.onerror = function () {
            console.error('There was a problem with the request.');
        };
        xhr.send(JSON.stringify(data));
    }
});
changeForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const op = Query('#op');
    const np = Query('#np');
    const cp = Query('#cp');

    if (op.value.trim() == '' || np.value.trim() == '' || cp.value.trim() == '') {
        alert(`Please fill all the fields`);
        return;
    }

    const isOldPasswordValid = validatePassword(op.value);
    if (!isOldPasswordValid) {
        changeForm.reset();
        alert(`Invalid old password`);
        return;
    }
    const isNewPasswordValid = validatePassword(np.value, npErr);
    const isConfirmPasswordValid = validateConfirmPassword(np.value, cp.value, cpErr);

    if (isNewPasswordValid && isConfirmPasswordValid) {
        if (op.value === np.value) {
            alert(`New password cannot be same as old password`);
            changeForm.reset();
            return;
        }
        const data = {
            "op": op.value,
            "np": np.value
        };

        const xhr = new XMLHttpRequest();
        xhr.open('POST', './requests/change-password.php');
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function () {
            if (xhr.status === 200) {
                const responseData = JSON.parse(xhr.responseText);
                if (responseData.success) {
                    changePassword.close();
                    document.body.style.overflow = 'auto';
                    document.body.style.marginRight = '0px';
                }
                alert(responseData.message);
            } else {
                console.error('Request failed. Status:', xhr.status);
            }
            changeForm.reset();
        };

        xhr.onerror = function () {
            console.error('There was a problem with the request.');
        };
        xhr.send(JSON.stringify(data));
    } else {
        changeForm.reset();
    }
});