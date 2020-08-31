<?php

// Check if the user is already logged in
function is_user_logged_in()
{
    if (
        isset($_SESSION['userLoginStatus']) &&
        $_SESSION['userLoginStatus'] == 1
    ) {
        return true;
    }
    return false;
}

// Check if the user is logged in and admin
function is_user_admin()
{
    if (
        is_user_logged_in() && isset($_SESSION['userIsAdmin'])
        && $_SESSION['userIsAdmin'] == 1
    ) {
        return true;
    }
    return false;
}

// Check if user is unlocked
function is_user_unlocked()
{
    if (
        is_user_logged_in() && isset($_SESSION['userIsUnlocked'])
        && $_SESSION['userIsUnlocked'] == 1
    ) {
        return true;
    }
    return false;
}

// Log the user out
function log_user_out()
{
    // delete Session
    $_SESSION = array();
    session_destroy();

    $cookiePath = array("/", "/shop", "/user", "/admin");

    // delete all 'XSS_YOUR_SESSION' and 'XSS_STOLEN_SESSION' cookies
    foreach ($cookiePath as $path) {
        setcookie("XSS_YOUR_SESSION", "", time() - 10800, $path);
        setcookie("XSS_STOLEN_SESSION", "", time() - 10800, $path);
    }

    header("location: " . "/index.php" . "?success=logout");
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

function unlock_user($username)
{
    // update database
    $sql = "UPDATE `users` SET `is_unlocked`=1 WHERE `user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "120");
        exit();
    }

    // update session
    $_SESSION['userIsUnlocked'] = 1;
}
