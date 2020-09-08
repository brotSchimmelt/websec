<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_ADMIN);
require(FUNC_LOGIN);
require(FUNC_SHOP);
require(FUNC_WEBSEC);

// check if export to JSON was requested
if (!isset($_POST['exportJSON'])) {
    header("location: " . "results.php");
    exit();
} else {
    // download JSON file
    header("Content-disposition: attachment; filename=results_"
        . date("H-i-s_d-m-Y") . ".json");
    header("Content-type: application/json");

    // make JSON file
    $json =  get_results_as_json();

    if (!$json || empty($json)) {
        header("location: " . "results.php");
        exit();
    }

    echo $json;
}
