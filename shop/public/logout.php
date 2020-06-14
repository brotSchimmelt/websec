<?php
session_start();

// includes
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (is_user_logged_in()) {

    $urlToken = $_GET['token'];
    if (isset($_SESSION['userToken']) && (!empty($_SESSION['userToken']))) {
        $sessionToken = $_SESSION['userToken'];
    } else {
        // TODO: Add error message handling
        echo "<h4>WARNING: Logout was unsuccessful. Session token is not set!</h4>";
        exit();
    }

    if (hash_equals($sessionToken, $urlToken)) {
        log_user_out();
    } else {
        // TODO: Add error message handling
        echo "<h4>WARNING: Logout was unsuccessful. Token mismatch!</h4>";
        exit();
    }
} else {
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}
