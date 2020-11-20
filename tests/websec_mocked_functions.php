<?php

function get_global_difficulty()
{
    return "normal";
}

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
            . 'target="_blank">Learnweb</a> forum.';
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

function save_challenge_solution($username, $solution, $challenge)
{

    $sql = "UPDATE `challenge_solutions` SET " . $challenge . " = :solution "
        . "WHERE `user_name` = :user";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            "solution" => $solution,
            "user" => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "169");
        exit();
    }
}
