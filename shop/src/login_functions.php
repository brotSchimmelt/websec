<?php

function get_login_db()
{
    static $dbLogin;

    if ($dbLogin instanceof PDO) {
        return $dbLogin;
    }

    try {
        $dbLogin = new PDO(DSN, DB_USER, DB_PWD, OPTIONS);
    } catch (PDOException $e) {
        exit("Unable to connect to the database :(");
    }
    return $dbLogin;
}


// check if username is in scope and 2 <= len <= 64
function validate_username($username)
{
    if (!preg_match("/^[A-Za-z0-9]*$/", $username)) {
        return false;
    } else if ((mb_strlen($username) > 64) || (mb_strlen($username) < 2)) {
        return false;
    }
    return true;
}

function validate_mail($mailAddress)
{
    if ((mb_strstr($mailAddress, "@") == "@uni-muenster.de") || ((mb_strstr($mailAddress, "@") == "@wi.uni-muenster.de"))) {
        return true;
    }
    return false;
}

function validate_pwd($pwd)
{
    if (!$pwd || mb_strlen($pwd) < 8) {
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

    $token = bin2hex(openssl_random_pseudo_bytes(32));

    $_SESSION['userToken'] = $token;
    $_SESSION['userName'] = $username;
    $_SESSION['userMail'] = $mail;
    $_SESSION['userLoginStatus'] = 1;

    if ($adminFlag == 1) {
        $_SESSION['userIsAdmin'] = $adminFlag;
    }
}

function slug($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}
