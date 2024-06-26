<?php

include_once('scripts/utils.php');
include_once('scripts/db.php');

function form_api_get($request, $user_id) {
    if (intval($user_id) != $_SESSION['user_id']) {
        $result = access_denied();
        $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        return $result;
    }

    $submission = get_user_form_submission($user_id);
    if (!$submission) {
        $result = bad_request();
        $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        return $result;
    }
    $submission_id = $submission[0]["id"];

    $values = array();

    $values["name"] = sanitize($submission[0]['name']);
    $values["phone"] = sanitize($submission[0]['phone']);
    $values["email"] = sanitize($submission[0]['email']);
    $values["date"] = sanitize($submission[0]['bdate']);
    $values["gender"] = sanitize($submission[0]['gender']);
    $values["bio"] = sanitize($submission[0]['bio']);
    
    $fpls = get_user_fpls($submission_id);
    if (!$fpls) {
        $result = bad_request();
        $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        return $result;
    }
    $values["fpls"] = sprintf("@%s@", implode("@", array_map('sanitize', $fpls)));

    return array(
        'headers' => array('Content-Type' => 'application/json'),
        'entity' => json_encode($values)
    );
}

function form_api_post($request) {
    if (isset($_POST['user_id'])) {
        return form_api_put($request, $_POST['user_id']);
    }

    $values = parse_form_submission_from_post($_POST);
    if (!validate_fields_and_set_cookies($values)) {
        $result = bad_request();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    $user_login = generate_login();
    $user_password = generate_password();
    $user_password_hash = get_password_hash($user_password);

    $user_data = array(
        "login" => $user_login,
        "password" => $user_password
    );

    $user_id = write_new_user($user_login, $user_password_hash);
    if ($user_id == -1) {
        $result = internal_server_error();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    setcookie('login', $user_login, time() + 60*60*24*365, "/");
    setcookie('password', $user_password, time() + 60*60*24*365, "/");

    $is_written = save_form_submission($user_id, $values);
    if (!$is_written) {
        $result = internal_server_error();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    if (isset($request['get']['js'])) {
        return array(
            'headers' => array('Content-Type' => 'application/json'),
            'entity' => json_encode($user_data)
        );
    }
    else {
        return array(
            'headers' => array('Content-Type' => 'application/json', 'Location' => '/'),
            'entity' => json_encode($user_data)
        );
    }
}

function form_api_put($request, $user_id) {
    if (!empty($_POST)) {
        $post_data = $_POST;
    }
    else {
        $post_data = array();
        parse_raw_http_request($post_data);
    }

    if (!isset($post_data['user_id']) || $user_id != $post_data['user_id'] || $user_id != $_SESSION['user_id'] || $post_data['user_id'] != $_SESSION['user_id']) {
        $result = access_denied();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    $values = parse_form_submission_from_post($post_data);
    if (!validate_fields_and_set_cookies($values)) {
        $result = bad_request();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    $update_status = update_sumbission_data($user_id, $values);
    if (!$update_status) {
        $result = internal_server_error();
        if (!isset($request['get']['js'])) {
            $result['headers'] = array_merge($result['headers'], array('Location' => '/'));
        }
        return $result;
    }

    if (!isset($request['get']['js'])) {
        return array(
            'headers' => array('Location' => '/user/' . $user_id)
        );
    }
    else {
        return array(
            'headers' => array('HTTP/1.1 200 OK')
        );
    }
    
}
