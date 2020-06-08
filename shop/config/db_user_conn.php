<?php

require("$_SERVER[DOCUMENT_ROOT]/../config/db_credentials.php");

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$CHARSET";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdoLogin = new PDO($dsn, $DB_USER, $DB_PWD, $options);
} catch (Exception $e) {
    // TODO: add verbose error message 
    echo ":(";
}
