<?php

// checks if the user is already logged in
function is_user_logged_in()
{
    if (isset($_SESSION['userLoginStatus']) && $_SESSION['userLoginStatus'] == 1) {
        return true;
    }
    return false;
}

// check if the user is logged in as an admin
function is_user_admin()
{
    if (is_user_logged_in() && isset($_SESSION['userIsAdmin']) && $_SESSION['userIsAdmin'] == 1) {
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
    exit();
}

function get_semester()
{
    $moduleName = " VM Web Security ";
    $semester = "";
    $month = date("n");
    $year = date("Y");

    if ($month <= 9) {
        $semester = "Summer Term ";
    } else {
        $semester = "Winter Term ";
    }

    echo $moduleName . $semester . $year;
}
