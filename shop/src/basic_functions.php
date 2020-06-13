<?php

function is_user_logged_in()
{
    if (isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] == 1) {
        return true;
    }

    return false;
}

function log_user_out()
{
    $_SESSION = array();
    session_destroy();
}
