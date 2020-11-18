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

function get_shop_db()
{
    static $dbShop;

    if ($dbShop instanceof PDO) {
        return $dbShop;
    }
    require_once(CONF_DB_SHOP);
    try {
        $dbShop = new PDO(DSN_SHOP, DB_USER_SHOP, DB_PWD_SHOP, OPTIONS_SHOP);
    } catch (PDOException $e) {
        $note = "The connection to our database could not be established. "
            . 'If this error persists, please post it to the '
            . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
            . 'target="_blank">Learnweb</a> forum.';
        display_exception_msg(null, "020", $note);
        exit();
    }
    return $dbShop;
}

function lookup_challenge_status($challenge, $username)
{
    // filter challenge name since prepared statements do not work for
    // table names etc.
    $challengeField = filter_var($challenge, FILTER_SANITIZE_SPECIAL_CHARS);

    if (get_global_difficulty() == "hard") {
        $challengeField = $challengeField . "_hard";
    }

    $sql = "SELECT " . $challengeField . " FROM `challengeStatus` WHERE "
        . "`user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        display_exception_msg($e, "125");
        exit();
    }

    // check if challenge was already solved
    return $result[$challengeField] == 1 ? true : false;
}

function get_global_difficulty()
{
    return "normal";
}
