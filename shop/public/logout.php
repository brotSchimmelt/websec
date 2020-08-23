<?php
session_start();

// includes
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);
require(ERROR_HANDLING);

if (is_user_logged_in()) {

    $urlToken = $_GET['token'];
    if (isset($_SESSION['userToken']) && (!empty($_SESSION['userToken']))) {
        $sessionToken = $_SESSION['userToken'];
    } else {
        $warning = "Logout was unsuccessful. Session token is not set!";
        display_warning_msg($warning);
        exit();
    }

    if (hash_equals($sessionToken, $urlToken)) {
        log_user_out();
    } else {
        $warning = "Logout was unsuccessful. Token mismatch!";
        display_warning_msg($warning);
        exit();
    }
} else {
    header("location: " . LOGIN_PAGE . "?success=logout");
    exit();
}
