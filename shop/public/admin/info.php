<?php
session_start();

// include config and basic functions
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (!is_user_logged_in()) {
    header("location: " . LOGIN_PAGE . "?login=accessDenied");
    exit();
}

if (is_user_admin()) {
    phpinfo();
} else {
    header("location: " . MAIN_PAGE);
    exit();
}
