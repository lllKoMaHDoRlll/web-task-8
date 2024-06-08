
function sendSubmission (ev) {
    ev.preventDefault();
    const formEl = document.getElementById("submission-form");
    const fields = new FormData(formEl);
    
    fields.entries().forEach(element => {
        console.log(element);
    });
    
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

function isFormFieldsValid () {

}

document.addEventListener('DOMContentLoaded', () => {
    submissionFormEl = document.getElementById("submission-form");
    if (submissionFormEl) {
        submissionFormEl.addEventListener('submit', sendSubmission);
    }
});