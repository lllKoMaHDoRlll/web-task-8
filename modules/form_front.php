<?php

include_once("scripts/utils.php");

function form_front_get($request, $user_id="-1") {
    if ($user_id !== "-1" && intval($user_id) !== $_SESSION['user_id']) {
        return access_denied();
    }

    $values = parse_submission_from_cookies();
    return theme('form', ['values' => $values]);
}
