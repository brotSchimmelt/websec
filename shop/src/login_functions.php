<?php

// check if username is in scope and 2 <= len <= 64
function validate_username($username)
{
    if (!preg_match("/^[A-Za-z0-9]*$/", $username)) {
        return false;
    } else if ((strlen($username) > 64) || (strlen($username) < 2)) {
        return false;
    }
    return true;
}

function validate_mail($mailAddress)
{
    if ((strstr($mailAddress, "@") == "@uni-muenster.de") || ((strstr($mailAddress, "@") == "@wi.uni-muenster.de"))) {
        return true;
    }
    return false;
}

function validate_pwd($pwd)
{
    if (!$pwd || strlen($pwd) < 8) {
        return false;
    }
    return true;
}

function hash_user_pwd($pwd)
{
    $hash = password_hash($pwd, PASSWORD_DEFAULT, ['cost' => 13]);
    // check if the hash was successfully created
    if (!$hash) {
        throw new Exception("Hash creation failed: Error Code 42. Please post this error in Learnweb.");
    }
    return $hash;
}

function do_login($username, $mail, $adminFlag)
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session has not yet started
        session_start();
    }

    $_SESSION['user_name'] = $username;
    $_SESSION['user_mail'] = $mail;
    $_SESSION['user_login_status'] = 1;

    if ($adminFlag == 1) {
        $_SESSION['user_is_admin'] = $adminFlag;
    }
}
