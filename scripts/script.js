
function sendSubmission (ev) {
    ev.preventDefault();
    const formEl = document.getElementById("submission-form");
    const fields = new FormData(formEl);
    
    if (!areFormFieldsValid(fields)) {
        return;
    }

    
    const request = new XMLHttpRequest();

    let method = "POST";
    let userIdPath = "";
    if (fields.get("user_id")) {
        method = "PUT";
        userIdPath = "/" + fields.get("user_id");
    }

    const url = "/api/form" + userIdPath + "?js=1";
    request.open(method, url);
    request.setRequestHeader("ACCEPT", "application/json");

    request.onreadystatechange = () => {
        if (request.readyState === XMLHttpRequest.DONE) {
            const requestStatus = request.status;
            if (requestStatus === 0 || (requestStatus >= 200 && requestStatus < 400)) {
                alert("Form was sended successfully");
                if (request.responseText && request.responseText !== "") {
                    const user_data = JSON.parse(request.responseText);
                    showUserData(user_data);
                }
            }
            else if (requestStatus === 403) {
                alert("Form was not sended. You were trying to access different user's submission!");
            }
            else if (requestStatus === 400) {
                alert("Form was not sended. Bad request!");
            }
            else {
                alert("Form was not sended.");
            }
        }
    };

    request.send(fields);
}

function areFormFieldsValid (fields) {
    let areFormFieldsValid = true;

    const nameFieldValue = fields.get("field-name");
    if (!nameFieldValue || nameFieldValue.length > 150 || !nameFieldValue.match(/^[a-zA-Z-' ]*$/u)) {
        const nameFieldEl = document.getElementsByName("field-name").length > 0 ? document.getElementsByName("field-name")[0] : null;
        if (nameFieldEl) {
            nameFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const phoneFieldValue = fields.get("field-phone");
    if (!phoneFieldValue || !phoneFieldValue.match(/^[0-9\-\+]{9,15}$/i)) {
        const phoneFieldEl = document.getElementsByName("field-phone").length > 0 ? document.getElementsByName("field-phone")[0] : null;
        if (phoneFieldEl) {
            phoneFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const emailFieldValue = fields.get("field-email");
    if (!emailFieldValue || !emailFieldValue.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
        const emailFieldEl = document.getElementsByName("field-email").length > 0 ? document.getElementsByName("field-email")[0] : null;
        if (emailFieldEl) {
            emailFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const dateFieldValue = fields.get("field-date");
    if (!dateFieldValue || !dateFieldValue.match(/^\d{4}-\d{2}-\d{2}$/i)) {
        const dateFieldEl = document.getElementsByName("field-date").length > 0 ? document.getElementsByName("field-date")[0] : null;
        if (dateFieldEl) {
            dateFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const genderFieldValue = fields.get("field-gender");
    if (!genderFieldValue || (genderFieldValue !== "male" && genderFieldValue !== "female")) {
        const genderFieldEl = document.getElementsByName("field-gender").length > 0 ? document.getElementsByName("field-gender")[0] : null;
        if (genderFieldEl) {
            genderFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const plFieldValue = fields.getAll("field-pl[]");
    const availableFpls = ["pascal", "c", "cpp", "js", "php", "python", "java", "haskel", "clojure", "prolog", "scala"];
    if (!plFieldValue || plFieldValue.length < 1 || plFieldValue.some((pl) => !availableFpls.includes(pl))) {
        const plFieldEl = document.getElementsByName("field-pl[]").length > 0 ? document.getElementsByName("field-pl[]")[0] : null;
        if (plFieldEl) {
            plFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const acceptionFieldValue = fields.get("check-accept");
    if (!acceptionFieldValue || acceptionFieldValue !== "accepted") {
        const acceptionFieldEl = document.getElementsByName("check-accept").length > 0 ? document.getElementsByName("check-accept")[0] : null;
        if (acceptionFieldEl) {
            acceptionFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    const bioFieldValue = fields.get("field-bio");
    if (!bioFieldValue || bioFieldValue.length > 300) {
        const bioFieldEl = document.getElementsByName("field-bio").length > 0 ? document.getElementsByName("field-bio")[0] : null;
        if (bioFieldEl) {
            bioFieldEl.classList.add("err_input");
        }
        areFormFieldsValid = false;
    }

    return areFormFieldsValid;
}

function clearFieldErrorStyles(ev) {
    if (ev && ev.target) {
        ev.target.classList.remove("err_input");
    }
}

function showUserData(user_data) {
    loginDataContainerEl = document.getElementById("login-data-container");
    if (loginDataContainerEl) {
        loginDataContainerEl.children[0].innerHTML = `You can <a href='./login'>login</a> with login: ${user_data['login']} and password: ${user_data['password']}.`;
        loginDataContainerEl.style.display = "block";
    }
}

document.addEventListener('DOMContentLoaded', () => {
    submissionFormEl = document.getElementById("submission-form");
    if (submissionFormEl) {
        submissionFormEl.addEventListener('submit', sendSubmission);
    }

    const nameFieldEl = document.getElementsByName("field-name").length > 0 ? document.getElementsByName("field-name")[0] : null;
    if (nameFieldEl) {
        nameFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const phoneFieldEl = document.getElementsByName("field-phone").length > 0 ? document.getElementsByName("field-phone")[0] : null;
    if (phoneFieldEl) {
        phoneFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const emailFieldEl = document.getElementsByName("field-email").length > 0 ? document.getElementsByName("field-email")[0] : null;
    if (emailFieldEl) {
        emailFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const dateFieldEl = document.getElementsByName("field-date").length > 0 ? document.getElementsByName("field-date")[0] : null;
    if (dateFieldEl) {
        dateFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const genderFieldEl = document.getElementsByName("field-gender").length > 0 ? document.getElementsByName("field-gender")[0] : null;
    if (genderFieldEl) {
        genderFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const plFieldEl = document.getElementsByName("field-pl[]").length > 0 ? document.getElementsByName("field-pl[]")[0] : null;
    if (plFieldEl) {
        plFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const acceptionFieldEl = document.getElementsByName("check-accept").length > 0 ? document.getElementsByName("check-accept")[0] : null;
    if (acceptionFieldEl) {
        acceptionFieldEl.addEventListener("input", clearFieldErrorStyles);
    }

    const bioFieldEl = document.getElementsByName("field-bio").length > 0 ? document.getElementsByName("field-bio")[0] : null;
    if (bioFieldEl) {
        bioFieldEl.addEventListener("input", clearFieldErrorStyles);
    }
});