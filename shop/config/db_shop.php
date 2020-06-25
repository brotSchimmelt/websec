<?php

// Dummy credentials from the docker example.env file
// TODO: change credentials to real ones
define("DB_HOST", "db_shop");
define("DB_NAME", "shop");
define("DB_USER", "root");
define("DB_PWD", "root");
define("CHARSET", "utf8mb4");
define(
    "DSN",
    "mysql:host=" . DB_HOST .
        ";dbname=" . DB_NAME .
        ";charset=" . CHARSET
);
define(
    "OPTIONS",
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);
