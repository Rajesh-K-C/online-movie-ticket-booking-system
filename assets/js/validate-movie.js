import Query from "./query";
export function validateMovie(form, update = false) {
    form.preventDefault();

    const movieName = Query("#name").value.trim();
    const movieDescription = Query("#description").value.trim();
    const movieLanguage = Query("#language").value;
    const movieRelease = Query("#release").value;
    const movieDuration = Number.parseInt(Query("#duration").value.trim());
    const image = Query("#image").files[0];

    const nameErr = Query(".nameErr");
    const descriptionErr = Query(".descriptionErr");
    const languageErr = document.querySelector(".languageErr");
    const releaseErr = Query(".releaseErr");
    const durationErr = Query(".durationErr");

    let isNameValid = false;
    let isDescriptionValid = false;
    let isLanguageValid = false;
    let isReleaseValid = false;
    let isDurationValid = false;
    let isImageValid = false;

    if (movieName == "") {
        nameErr.textContent = "Please enter movie name";
    } else if (!/([a-z]|\d)+/i.test(movieName)) {
        nameErr.textContent = "Movie names must contain at least one alphabetic character or one digit.";
    } else {
        nameErr.textContent = "";
        isNameValid = true;
    }

    if (movieDescription.length > 10 && movieDescription.length <= 1000) {
        descriptionErr.textContent = "";
        isDescriptionValid = true;
    } else {
        descriptionErr.textContent = "Description between 10 and 1000 characters";
    }

    if (movieLanguage != "") {
        isLanguageValid = true;
        languageErr.textContent = "";
    } else {
        languageErr.textContent = "Please select a language";
    }

    if (movieRelease.length != "") {
        isReleaseValid = true;
        releaseErr.textContent = "";
    } else {
        releaseErr.textContent = "Please select release date";
    }

    if (movieDuration >= 60 && movieDuration <= 200) {
        isDurationValid = true;
        durationErr.textContent = "";
    } else {
        durationErr.textContent = "Duration must be between 60 and 200 minutes";
    }
    if (!update) {
        const imageErr = Query(".imageErr");
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (image && allowedTypes.includes(image.type)) {
            console.log('File type is valid');
            isImageValid = true;
            imageErr.textContent = "";
        } else if (image) {
            imageErr.textContent = "Invalid file type. Please upload a file of type: JPEG, PNG, or JPG.";

        } else {
            imageErr.textContent = "Please select an image";
        }
    } else {
        const imageErr = Query(".imageErr");
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (image && allowedTypes.includes(image.type)) {
            console.log('File type is valid');
            isImageValid = true;
            imageErr.textContent = "";
        } else if (image) {
            imageErr.textContent = "Invalid file type. Please upload a file of type: JPEG, PNG, or JPG.";

        } else {
            imageErr.textContent = "";
            isImageValid = true;
        }
    }

    if (isNameValid && isDescriptionValid && isLanguageValid &&
        isReleaseValid && isDurationValid && isImageValid) {
        form.target.submit();
    }
}