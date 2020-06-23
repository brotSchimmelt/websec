<?php

// Dummy credentials from the docker example.env file
// TODO: change credentials to real ones
define("DB_HOST", "db_login");
define("DB_NAME", "login");
define("DB_USER", "root");
define("DB_PWD", "root");
define("CHARSET", "utf8mb4");


$dsn =
    "mysql:host=" . DB_HOST .
    ";dbname=" . DB_NAME .
    ";charset=" . CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdoLogin = new PDO($dsn, DB_USER, DB_PWD, $options);
} catch (Exception $e) {
    // TODO: add verbose error message 
    echo "whoops, the database connection could not be established:-(";
}
