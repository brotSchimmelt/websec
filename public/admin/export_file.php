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
if (isset($_POST['exportJSON'])) {

    // get data and name
    $jsonName = "websec_results_" . date("H-i-s_d-m-Y") . ".json";
    $data = get_results_as_array();

    // output json file
    echo export_json($data, $jsonName);

    // check if export to CSV was requested
} else if (isset($_POST['exportCSV'])) {

    // get data and name
    $csvName = "websec_results_" . date("H-i-s_d-m-Y") . ".csv";
    $data = get_results_as_array();

    export_csv($data, $csvName);
} else {
    header("location: " . "results.php");
    exit();
}
