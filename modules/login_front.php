<?php

function login_front_get($request) {
    if (isset($_SESSION['user_id'])) {
        return array('headers' => array('Location' => '/user/' . $_SESSION['user_id']));
    }
    return theme('login');
}