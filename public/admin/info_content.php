<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// load basic functions
require(FUNC_BASE);

// check if user is admin before showing info.php
if (is_user_admin()) {
    // show php configurations
    phpinfo();
} else {
    // redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}
