<?php

function get_global_difficulty()
{
    return "normal";
}

function create_sqli_db($username, $mail)
{
}

function slug($str)
{
    return $str;
}

function get_blocked_usernames()
{
    return ["admin", "elliot", "l337_h4ck3r", "administrator"];
}

function get_allowed_domains()
{
    return ["@uni-muenster.de", "@wi.uni-muenster.de"];
}

function get_user_name()
{
    return "username";
}

function send_mail($foo, $bar, $foobar, $barfoo)
{
    return true;
}

function get_shop_db()
{
    static $dbShop;

    if ($dbShop instanceof PDO) {
        return $dbShop;
    }
    require_once(dirname(__FILE__) . CONF_DB_SHOP); // DB credentials
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
