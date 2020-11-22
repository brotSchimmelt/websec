<?php

function get_login_db()
{
    // ensure only one connection at a time is alive
    static $dbLogin;
    if ($dbLogin instanceof PDO) {
        return $dbLogin;
    }

    try {
        $dbLogin = new PDO(DSN_LOGIN, DB_USER_LOGIN, DB_PWD_LOGIN, OPTIONS_LOGIN);
    } catch (PDOException $e) {
        $note = "The connection to our database could not be established. "
            . 'If this error persists, please post it to the '
            . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
            . ' target="_blank">Learnweb</a> forum.';
        display_exception_msg(null, "010", $note);
        exit();
    }
    return $dbLogin;
}

function lookup_challenge_status($a, $b)
{
    return true;
}
