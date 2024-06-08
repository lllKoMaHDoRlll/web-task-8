<?php

include_once("scripts/utils.php");

function form_front_get($request, $user_id="-1") {
    if ($user_id !== "-1" && intval($user_id) !== $_SESSION['user_id']) {
        return access_denied();
    }

    $values = parse_submission_from_cookies();
    if ($user_id !== "-1" && intval($user_id) === $_SESSION['user_id']) {
        $submission = get_user_form_submission($user_id);
        if (!empty($submission)) {
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

        }
    }
    clear_user_data_cookies();
    return theme('form', ['values' => $values]);
}
