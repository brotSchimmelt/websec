<?php

// Dummy credentials from the docker .env file
$dbHost = 'db_shop';
$dbName   = 'shop';
$dbUser = 'shop';
$dbPass = 'shop';
$charset = 'utf8mb4';

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdoLogin = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (Exception $e) {
    // TODO: add verbose error message 
    echo "upps";
}
