<?php
session_start();

// includes
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

$urlToken = $_GET['token'];
if (isset($_SESSION['user_token']) && (!empty($_SESSION['user_token']))) {
    $sessionToken = $_SESSION['user_token'];
} else {
    echo "<h4>WARNING: Logout was unsuccessful. Session token is not set!</h4>";
    exit();
}

if (hash_equals($sessionToken, $urlToken)) {
    log_user_out();
} else {
    echo "<h4>WARNING: Logout was unsuccessful. Token mismatch!</h4>";
    exit();
}
