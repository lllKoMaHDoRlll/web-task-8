<?php

function sanitize($string) {
    return htmlspecialchars($string);
}

function parse_submission_from_cookies() {
    $values = array();
    $values["name"] = empty($_COOKIE['field-name']) ? '' : sanitize($_COOKIE['field-name']);
    $values["phone"] = empty($_COOKIE['field-phone']) ? '' : sanitize($_COOKIE['field-phone']);
    $values["email"] = empty($_COOKIE['field-email']) ? '' : sanitize($_COOKIE['field-email']);
    $values["date"] = empty($_COOKIE['field-date']) ? '' : sanitize($_COOKIE['field-date']);
    $values["gender"] = empty($_COOKIE['field-gender']) ? '' : (sanitize($_COOKIE['field-gender']) == "male"? '1' : '0');
    $values["fpls"] = empty($_COOKIE['field-pl']) ? '' : sanitize($_COOKIE['field-pl']);
    $values["bio"] = empty($_COOKIE['field-bio']) ? '' : sanitize($_COOKIE['field-bio']);

    return $values;
}

function parse_form_submission_from_post() {
    $submission = array();
    $submission['name'] = sanitize($_POST['field-name']);
    $submission['phone'] = sanitize($_POST['field-phone']);
    $submission['email'] = sanitize($_POST['field-email']);
    $submission['date'] = sanitize($_POST['field-date']);
    $submission['gender'] = sanitize($_POST['field-gender']);
    $submission['bio'] = sanitize($_POST['field-bio']);
    $submission['fpls'] = array_map('sanitize', $_POST['field-pl']);
    $submission['acception'] = sanitize($_POST['check-accept']);

    return $submission;
}

function validate_fields_and_set_cookies($values)
{
    $expiration_time_on_error = 0;
    $expiration_time_on_success = time() + 60*60*24*365;
    $validation_passed = True;

    if (empty($values["name"]) || strlen($values["name"]) > 150 || !preg_match("/^[\p{Cyrillic}a-zA-Z-' ]*$/u", $values["name"])) {
        setcookie("field-name-error", "1", $expiration_time_on_error, "/");
        setcookie('field-name', $values["name"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-name', $values["name"], $expiration_time_on_success, "/");
        setcookie("field-name-error", "", 1, "/");
    }
    
    if (empty($values["phone"]) || !preg_match('/[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}/i', $values["phone"])) {
        setcookie("field-phone-error", "1", $expiration_time_on_error, "/");
        setcookie('field-phone', $values["phone"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-phone', $values["phone"], $expiration_time_on_success, "/");
        setcookie("field-phone-error", "", 1, "/");
    }

    if (empty($values["email"]) || !filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
        setcookie("field-email-error", "1", $expiration_time_on_error, "/");
        setcookie('field-email', $values["email"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-email', $values["email"], $expiration_time_on_success, "/");
        setcookie("field-email-error", "", 1, "/");
    }

    if (empty($values["date"]) || !preg_match('/^\d{4}-\d{2}-\d{2}$/i', $values["date"])) {
        setcookie("field-date-error", "1", $expiration_time_on_error, "/");
        setcookie('field-date', $values["date"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-date', $values["date"], $expiration_time_on_success, "/");
        setcookie("field-date-error", "", 1, "/");
    }

    if (empty($values["gender"]) || !preg_match('/^\Qmale\E|\Qfemale\E$/i', $values["gender"])) {
        setcookie("field-gender-error", "1", $expiration_time_on_error, "/");
        setcookie('field-gender', $values["gender"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-gender', $values["gender"], $expiration_time_on_success, "/");
        setcookie("field-gender-error", "", 1, "/");
    }

    if (empty($values["fpls"]) || count($values["fpls"]) < 1 || !preg_match('/^((\Qpascal\E|\Qc\E|\Qcpp\E|\Qjs\E|\Qphp\E|\Qpython\E|\Qjava\E|\Qhaskel\E|\Qclojure\E|\Qprolog\E|\Qscala\E){1}[\,]{0,1})+$/i', implode(",", $values["fpls"]))) {
        setcookie("field-pl-error", "1", $expiration_time_on_error, "/");
        setcookie('field-pl', sprintf("@%s@", implode("@", $values["fpls"])), $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-pl', sprintf("@%s@", implode("@", $values["fpls"])), $expiration_time_on_success, "/");
        setcookie("field-pl-error", "", 1, "/");
    }

    if (empty($values["acception"]) || $values["acception"] != "accepted") {
        setcookie("field-accept-error", "1", $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie("field-accept-error", "", 1, "/");
    }

    if (strlen($values["bio"]) > 300) {
        setcookie("field-bio-error", "1", $expiration_time_on_error, "/");
        setcookie('field-bio', $values["bio"], $expiration_time_on_error, "/");
        $validation_passed = False;
    }
    else {
        setcookie('field-bio', $values["bio"], $expiration_time_on_success, "/");
        setcookie("field-bio-error", "", 1, "/");
    }

    return $validation_passed;
}

function generate_login() {
    return uniqid();
}

function generate_password() {
    return rand();
}

function get_password_hash($password) {
    return md5($password);
}

function generate_csrf_token() {
    return md5(uniqid(mt_rand(), true));
}