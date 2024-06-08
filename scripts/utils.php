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

function parse_form_submission_from_post($post_data) {
    $submission = array();
    $submission['name'] = isset($post_data['field-name']) ? sanitize($post_data['field-name']) : "";
    $submission['phone'] = isset($post_data['field-phone']) ? sanitize($post_data['field-phone']) : "";
    $submission['email'] = isset($post_data['field-email']) ? sanitize($post_data['field-email']) : "";
    $submission['date'] = isset($post_data['field-date']) ? sanitize($post_data['field-date']) : "";
    $submission['gender'] = isset($post_data['field-gender']) ? sanitize($post_data['field-gender']) : "";
    $submission['bio'] = isset($post_data['field-bio']) ? sanitize($post_data['field-bio']) : "";
    $submission['fpls'] = isset($post_data['field-pl']) ? array_map('sanitize', $post_data['field-pl']) : array();
    $submission['acception'] = isset($post_data['check-accept']) ? sanitize($post_data['check-accept']) : "";

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

function get_user_db_data($login, $password_hash)
{
    global $db;
    try {
        $stmt = $db->prepare('SELECT user_id FROM users WHERE
        login = :login AND password_hash = :password_hash');
        $stmt->bindParam('login', $login);
        $stmt->bindParam('password_hash', $password_hash);
        $stmt->execute();
        
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return false;
    }
}

function get_user_id($login, $password_hash) {
    $result = get_user_db_data($login, $password_hash);
    if (!$result || count($result) == 0) {
        return -1;
    }
    else {
        return $result[0]['user_id'];
    }
}

function parse_raw_http_request(array &$a_data)
{
  // read incoming data
  $input = file_get_contents('php://input');
  
  // grab multipart boundary from content type header
  preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
  $boundary = $matches[1];
  
  // split content by boundary and get rid of last -- element
  $a_blocks = preg_split("/-+$boundary/", $input);
  array_pop($a_blocks);
      
  // loop data blocks
  foreach ($a_blocks as $id => $block)
  {
    if (empty($block))
      continue;
    
    // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
    
    // parse uploaded files
    if (strpos($block, 'application/octet-stream') !== FALSE)
    {
      // match "name", then everything after "stream" (optional) except for prepending newlines 
      preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
    }
    // parse all other fields
    else
    {
      // match "name" and optional value in between newline sequences
      preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
    }
    array_shift($matches);
    if (str_ends_with($matches[0], "[]")) {
        $key = substr($matches[0], 0, strlen($matches[0]) - 2);
        if (!isset($a_data[$key])) {
            $a_data[$key] = array($matches[1]);
        }
        else {
            array_push($a_data[$key], $matches[1]);
        }
    }
    $a_data[$matches[0]] = $matches[1];
  }        
}

function clear_user_data_cookies() {
    setcookie("login", "0", 1, "/");
    setcookie("password", "0", 1, "/");
}
