<?php

// Check if the user is already logged in
function is_user_logged_in()
{
    if (isset($_SESSION['userLoginStatus']) && $_SESSION['userLoginStatus'] == 1) {
        return true;
    }
    return false;
}

// Check if the user is logged in and admin
function is_user_admin()
{
    if (is_user_logged_in() && isset($_SESSION['userIsAdmin']) && $_SESSION['userIsAdmin'] == 1) {
        return true;
    }
    return false;
}

// Check if user is unlocked
// TODO: add session var and if false redirect to overview page
function is_user_unlocked()
{
    return true;
}

// Log the user out
function log_user_out()
{
    $_SESSION = array();
    session_destroy();

    header("location: " . "/index.php" . "?login=loggedOut");
    exit();
}

// Return the current year and semester
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
