
function sendSubmission (ev) {
    ev.preventDefault();
    const formEl = document.getElementById("submission-form");
    const fields = new FormData(formEl);
    
    if (!areFormFieldsValid(fields)) {
        alert("Fields of form are not valid!");
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
            console.log(request);
        }
    };

    request.send(fields);
}

function areFormFieldsValid (fields) {
    let areFormFieldsValid = true;

    const nameFieldValue = fields.get("field-name");
    if (!nameFieldValue || nameFieldValue.length > 150 || !nameFieldValue.match(/^[a-zA-Z-' ]*$/u)) {
        const nameFieldEl = document.getElementsByName("field-name").length > 0 ? document.getElementsByName("field-name")[0] : null;
        const nameFieldErrorDescriptionEl = document.getElementById("field-name-err-desc");
        if (nameFieldEl) {
            nameFieldEl.classList.add("err_input");
        }
        if(nameFieldErrorDescriptionEl) {
            nameFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const phoneFieldValue = fields.get("field-phone");
    if (!phoneFieldValue || !phoneFieldValue.match(/^[0-9\-\+]{9,15}$/i)) {
        const phoneFieldEl = document.getElementsByName("field-phone").length > 0 ? document.getElementsByName("field-phone")[0] : null;
        const phoneFieldErrorDescriptionEl = document.getElementById("field-phone-err-desc");
        if (phoneFieldEl) {
            phoneFieldEl.classList.add("err_input");
        }
        if(phoneFieldErrorDescriptionEl) {
            phoneFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const emailFieldValue = fields.get("field-email");
    if (!emailFieldValue || !emailFieldValue.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
        const emailFieldEl = document.getElementsByName("field-email").length > 0 ? document.getElementsByName("field-email")[0] : null;
        const emailFieldErrorDescriptionEl = document.getElementById("field-email-err-desc");
        if (emailFieldEl) {
            emailFieldEl.classList.add("err_input");
        }
        if(emailFieldErrorDescriptionEl) {
            emailFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const dateFieldValue = fields.get("field-date");
    if (!dateFieldValue || !dateFieldValue.match(/^\d{4}-\d{2}-\d{2}$/i)) {
        const dateFieldEl = document.getElementsByName("field-date").length > 0 ? document.getElementsByName("field-date")[0] : null;
        const dateFieldErrorDescriptionEl = document.getElementById("field-date-err-desc");
        if (dateFieldEl) {
            dateFieldEl.classList.add("err_input");
        }
        if(dateFieldErrorDescriptionEl) {
            dateFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const genderFieldValue = fields.get("field-gender");
    if (!genderFieldValue || (genderFieldValue !== "male" && genderFieldValue !== "female")) {
        const genderFieldEl = document.getElementsByName("field-gender").length > 0 ? document.getElementsByName("field-gender")[0] : null;
        const genderFieldErrorDescriptionEl = document.getElementById("field-gender-err-desc");
        if (genderFieldEl) {
            genderFieldEl.classList.add("err_input");
        }
        if(genderFieldErrorDescriptionEl) {
            genderFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const plFieldValue = fields.get("field-pl[]");
    if (!plFieldValue || plFieldValue.length < 1 || !plFieldValue.join(',').match(/^((\Qpascal\E|\Qc\E|\Qcpp\E|\Qjs\E|\Qphp\E|\Qpython\E|\Qjava\E|\Qhaskel\E|\Qclojure\E|\Qprolog\E|\Qscala\E){1}[\,]{0,1})+$/i)) {
        const plFieldEl = document.getElementsByName("field-pl[]").length > 0 ? document.getElementsByName("field-pl[]")[0] : null;
        const plFieldErrorDescriptionEl = document.getElementById("field-fpls-err-desc");
        if (plFieldEl) {
            plFieldEl.classList.add("err_input");
        }
        if(plFieldErrorDescriptionEl) {
            plFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const acceptionFieldValue = fields.get("check-accept");
    if (!acceptionFieldValue || acceptionFieldValue !== "accepted") {
        const acceptionFieldEl = document.getElementsByName("check-accept").length > 0 ? document.getElementsByName("check-accept")[0] : null;
        const acceptionFieldErrorDescriptionEl = document.getElementById("field-acception-err-desc");
        if (acceptionFieldEl) {
            acceptionFieldEl.classList.add("err_input");
        }
        if(acceptionFieldErrorDescriptionEl) {
            acceptionFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    const bioFieldValue = fields.get("field-bio");
    if (!bioFieldValue || bioFieldValue.length > 300) {
        const bioFieldEl = document.getElementsByName("field-bio").length > 0 ? document.getElementsByName("field-bio")[0] : null;
        const bioFieldErrorDescriptionEl = document.getElementById("field-bio-err-desc");
        if (bioFieldEl) {
            bioFieldEl.classList.add("err_input");
        }
        if(bioFieldErrorDescriptionEl) {
            bioFieldErrorDescriptionEl.style.display = "block";
        }
        areFormFieldsValid = false;
    }

    return areFormFieldsValid;
}

document.addEventListener('DOMContentLoaded', () => {
    submissionFormEl = document.getElementById("submission-form");
    if (submissionFormEl) {
        submissionFormEl.addEventListener('submit', sendSubmission);
    }
});