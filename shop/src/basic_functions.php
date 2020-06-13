<?php

// checks if the user is already logged in
function is_user_logged_in()
{
    if (isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] == 1) {
        return true;
    }
    return false;
}

// check if the user is logged in as an admin
function is_user_admin()
{
    if (is_user_logged_in() && isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'] == 1) {
        return true;
    }
    return false;
}

// logs the user out
function log_user_out()
{
    $_SESSION = array();
    session_destroy();

    header("location: " . "/index.php" . "?login=loggedOut");
}
