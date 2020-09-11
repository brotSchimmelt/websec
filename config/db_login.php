<?php

// Dummy credentials from the docker example.env file
// TODO: change credentials to real ones
define("DB_HOST_LOGIN", "db_login");
define("DB_NAME_LOGIN", "login");
define("DB_USER_LOGIN", "root");
define("DB_PWD_LOGIN", "root");
define("CHARSET_LOGIN", "utf8mb4");
define(
    "DSN_LOGIN",
    "mysql:host=" . DB_HOST_LOGIN .
        ";dbname=" . DB_NAME_LOGIN .
        ";charset=" . CHARSET_LOGIN
);
define(
    "OPTIONS_LOGIN",
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);
