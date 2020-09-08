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

    // make sure all cookies are deleted after logout
    delete_all_challenge_cookies();
    delete_all_cookies();

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

// delete all cookies for the XSS challenges
function delete_all_challenge_cookies()
{
    $cookiePath = array("/", "/shop", "/user", "/admin");

    // delete all 'XSS_YOUR_SESSION' and 'XSS_STOLEN_SESSION' cookies
    foreach ($cookiePath as $path) {
        setcookie("XSS_YOUR_SESSION", "", time() - 10800, $path);
        setcookie("XSS_STOLEN_SESSION", "", time() - 10800, $path);
    }
}

// delete all cookies set
function delete_all_cookies()
{

    $cookiePath = array("/", "/shop", "/user", "/admin");

    foreach ($_COOKIE as $key => $value) {
        foreach ($cookiePath as $path) {
            setcookie($key, $value, time() - 10800, $path);
        }
    }
}

// get the current value for the global challenge difficulty
function get_global_difficulty()
{
    try {
        // get setting
        $setting = get_setting('difficulty', 'hard');

        // check if difficulty is set to hard
        if ($setting === true) {
            return "hard";
        } else {
            return "normal";
        }
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// check if registration is enabled
function is_registration_enabled()
{
    try {
        // return inverted setting value
        return !get_setting("registration", "disabled");
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// check if login is enabled
function is_login_enabled()
{
    try {
        // return inverted setting value
        return !get_setting("login", "disabled");
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// wrapper function to read in JSON settings
function get_setting($setting, $subsetting)
{
    // load path to settings.json
    $file = SETTINGS;

    // load settings as assoc array
    $json = read_json_file($file);

    // check if setting is boolean
    if (!is_bool($json[$setting][$subsetting])) {
        throw new Exception($setting . ":" . $subsetting .
            " in settings.json is not a boolean value.");
    }

    return $json[$setting][$subsetting];
}

// wrapper function to write to JSON settings
function set_setting($setting, $subsetting, $newValue)
{
    // load path to settings.json
    $file = SETTINGS;

    // load settings as assoc array
    $json = read_json_file($file);

    // set new value
    $json[$setting][$subsetting] = $newValue;

    // write new settings file in place
    file_put_contents($file, json_encode($json));
}

// read json file as assoc array
function read_json_file($file)
{
    // read file in
    if (file_exists($file)) {
        // load settings as assoc array
        return json_decode(file_get_contents($file), true);
    } else {
        throw new Exception($file . " could not be opened.");
    }
}
