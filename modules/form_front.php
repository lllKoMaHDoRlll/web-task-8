<?php

include_once("scripts/utils.php");

function form_front_get($request, $user_id="-1") {
    echo $user_id;
    $values = parse_submission_from_cookies();
    return theme('form', ['values' => $values]);
}