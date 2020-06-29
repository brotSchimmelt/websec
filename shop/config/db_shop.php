<?php

// Dummy credentials from the docker example.env file
// TODO: change credentials to real ones
define("DB_HOST_SHOP", "db_shop");
define("DB_NAME_SHOP", "shop");
define("DB_USER_SHOP", "root");
define("DB_PWD_SHOP", "root");
define("CHARSET_SHOP", "utf8mb4");
define(
    "DSN_SHOP",
    "mysql:host=" . DB_HOST_SHOP .
        ";dbname=" . DB_NAME_SHOP .
        ";charset=" . CHARSET_SHOP
);
define(
    "OPTIONS_SHOP",
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);
