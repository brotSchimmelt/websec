<?php
session_start(); // needs to be called first on every page


/**
 * Check if the user has permission or the token to access phpMyAdmin.
 * This option is only available if phpMyAdmin and apache are installed in the 
 * same docker container. Otherwise, access phpMyAdmin via the open port.
 */


// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// load functions
require(FUNC_BASE);
require(ERROR_HANDLING);

if (is_user_admin()) {
    // redirect to phpMyAdmin
    header("location: " . ".." . DS . PMA);
    exit();
} else if (isset($_GET['token']) && $_GET['token'] == PMA_TOKEN) {
    // redirect to phpMyAdmin
    header("location: " . ".." . DS . PMA);
    exit();
} else {
    // show 404 page
    header("location: " . "/error.php?error=404");
    exit();
}
