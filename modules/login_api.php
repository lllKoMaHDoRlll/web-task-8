<?php

include_once('scripts/utils.php');
include_once('scripts/db.php');

function login_api_post($request) {

    $is_session_started = false;
    if(!empty($_COOKIE[session_name()]) && session_start()) {
        $is_session_started = true;
        if (!empty($_SESSION['login'])) {
            return array('headers' => array('Location' => '/user/' . $_SESSION['user_id']));
        }
    }

    $login = !empty($_POST['field-login'])? $_POST['field-login'] : "";
    $password = !empty($_POST['field-password'])? $_POST['field-password'] : "";
    $password_hash = get_password_hash($password);

    $user_id = get_user_id($login, $password_hash);

    if ($user_id == -1) {
        setcookie("login-error", "1", 0, "/");
        return array('headers' => array('Location' => '/login'));
    }
    else {
        setcookie("login-error", "", 1, "/");
        if (!$is_session_started) {
            session_start();
        }

        $_SESSION['login'] = $_POST['field-login'];
        $_SESSION['user_id'] = $user_id;

        return array('headers' => array('Location' => '/user/' . $_SESSION['user_id']));
    }
} 