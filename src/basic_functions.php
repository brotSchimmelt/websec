<?php

/**
 * This file contains functions that are used on almost every page or that are
 * so general in their design that they could not be grouped to a specific topic.
 */

/**
 * Check if the current user is already logged in.
 *
 * Check if the user is logged in the current session.
 */
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

/**
 * Check if the current user is logged in and admin.
 *
 * Check if the current user is logged in and admin in the current session.
 */
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

/**
 * Check if the current user is already unlocked.
 *
 * Check if the current user is unlocked in the login database.
 */
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

/**
 * Log the current user out.
 *
 * Destroy the current session and redirect user to the logout page.
 */
function log_user_out()
{
    // delete Session
    $_SESSION = array();
    session_destroy();

    // make sure all cookies are deleted after logout
    delete_all_challenge_cookies();
    delete_all_cookies();

    header("location: " . "/index.php" . "?success=logout");
    return false;
}

/**
 * Get the current semester.
 *
 * Get the seminar name, the current date and the semester as string.
 *
 * @return string Current semester.
 */
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

    return $moduleName . $semester . $year;
}

/**
 * Unlock the given user.
 *
 * Set the unlock flag for the current user in the login database.
 *
 * @param string $username Name of the given user.
 */
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

/**
 * Delete all challenge cookies.
 *
 * Delete all cookies for the XSS challenges with all possible paths.
 */
function delete_all_challenge_cookies()
{
    $cookiePath = array("/", "/shop", "/user", "/admin");

    // delete all 'XSS_YOUR_SESSION' and 'XSS_STOLEN_SESSION' cookies
    foreach ($cookiePath as $path) {
        setcookie("XSS_YOUR_SESSION", "", time() - 10800, $path);
        setcookie("XSS_STOLEN_SESSION", "", time() - 10800, $path);
    }
}

/**
 * Delete all cookies.
 *
 * Delete all cookies on the hacking platform.
 */
function delete_all_cookies()
{

    $cookiePath = array("/", "/shop", "/user", "/admin");

    foreach ($_COOKIE as $key => $value) {
        foreach ($cookiePath as $path) {
            setcookie($key, $value, time() - 10800, $path);
        }
    }
}

/**
 * The the global difficulty for the challenges.
 *
 * Return the current global difficulty in the settings.json file.
 *
 * @return string Global difficulty.
 */
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

/**
 * Check if the registration form is open.
 *
 * Check if the registration function is enabled in the settings.json file.
 *
 * @return bool Registration status.
 */
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

/**
 * Check if the login form is open.
 *
 * Check if the login function is enabled in the settings.json file.
 *
 * @return bool Login status.
 */
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

/**
 * Get the link to further information for the given challenge.
 *
 * Get the external link for further information on the given challenge from
 * the settings.json file.
 *
 * @param string $challenge Challenge.
 * @return string Badge link.
 */
function get_challenge_badge_link($challenge)
{
    try {
        return get_setting("badge_links", $challenge);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Get allowed mail domains for the registration.
 *
 * Get the allowed domains for the registration from the settings.json file.
 *
 * @return array List of allowed domains.
 */
function get_allowed_domains()
{
    try {
        return get_setting("domains", "allow_list");
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Get blocked user names for the registration.
 *
 * Get the blocked user names for the registration from the settings.json file.
 *
 * @return array List of blocked user names.
 */
function get_blocked_usernames()
{
    try {
        return get_setting("usernames", "deny_list");
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Read settings from the settings.json file.
 *
 * Wrapper function to read data from the settings.json file.
 *
 * @param string $setting Setting class.
 * @param string $subsetting Specific setting.
 * @return mixed Setting value.
 * @throws Exception If setting type does not match.
 */
function get_setting($setting, $subsetting, $file = SETTINGS)
{

    // load settings as assoc array
    try {
        $json = read_json_file($file);
    } catch (Exception $e) {
        display_exception_msg($e, "071");
        exit();
    }

    // check if setting is correct datatype
    if (in_array($setting, ["login", "registration", "difficulty"], true)) {
        if (!is_bool($json[$setting][$subsetting])) {
            throw new Exception($setting . ":" . $subsetting .
                " in settings.json is not a boolean value.");
        }
    } else if ($setting == "badge_links") {
        if (!is_string($json[$setting][$subsetting])) {
            throw new Exception($setting . ":" . $subsetting .
                " in settings.json is not a string value.");
        }
    } else if (in_array($setting, ["domains", "usernames"], true)) {
        if (!is_array($json[$setting][$subsetting])) {
            throw new Exception($setting . ":" . $subsetting .
                " in settings.json is not an array.");
        }
    }

    return $json[$setting][$subsetting];
}

/**
 * Write settings to the settings.json file.
 *
 * Wrapper function to wirte data to the settings.json file.
 *
 * @param string $setting Setting class.
 * @param string $subsetting Specific setting.
 * @param string|bool $newValue New setting value.
 * @throws Exception If setting type does not match.
 */
function set_setting($setting, $subsetting, $newValue, $file = SETTINGS)
{

    // load settings as assoc array
    try {
        $json = read_json_file($file);
    } catch (Exception $e) {
        display_exception_msg($e, "071");
        exit();
    }

    // check if setting is correct datatype
    if (in_array($setting, ["login", "registration", "difficulty"], true)) {
        if (!is_bool($newValue)) {
            throw new Exception($newValue . " is not a boolean value.");
        }
    } else if ($setting == "badge_links") {
        if (!is_string($newValue)) {
            throw new Exception($newValue . " is not a string value.");
        }
    } else if (in_array($setting, ["domains", "usernames"], true)) {
        if (!is_array($newValue)) {
            throw new Exception($newValue . " is not an array.");
        }
    }

    // set new value
    $json[$setting][$subsetting] = $newValue;

    // write new settings file in place
    file_put_contents($file, json_encode($json));
}

/**
 * Read in a given json file.
 *
 * The json file will be read in as an assoc array.
 *
 * @param string $file Path to json file.
 * @return array JSON data as array.
 * @throws Exception If file does not exits.
 */
function read_json_file($file)
{
    // read file in
    if (file_exists($file)) {
        // load content as assoc array
        return json_decode(file_get_contents($file), true);
    } else {
        throw new Exception($file . " could not be opened.");
    }
}

/**
 * Convert a string to a clean array.
 *
 * Remove all whitespace, coma from a given string and save it as an array.
 *
 * @param string $str Input string.
 * @return array Clean array.
 */
function make_clean_array($str)
{
    // delete whitespace
    $str = str_replace(" ", "", $str);

    // check if list is empty
    if (empty($str)) {
        return array();
    }

    // return array with 1 element
    if (strpos($str, ",") === false) {
        return [$str];
    }

    // remove last character if it is a coma
    $done = false;
    while (!$done) {

        if ($str[strlen($str) - 1] == ",") {
            $str = substr($str, 0, -1);
        } else {
            $done = true;
        }
    }

    // make string into array
    $arr = explode(",", $str);

    // filter empty or false elements from the array
    return array_filter($arr);
}

/**
 * Export a given array as a CSV file.
 *
 * Write a given array to a CSV file and set download headers.
 *
 * @param array $array Input array.
 * @param string $name Name of the CSV file.
 * @param string $delimiter Character to separat data points.
 * @param string $replaceCharacter Character to replace occurrences of the
 * delimiter in the input array.
 */
function export_csv($array, $name, $delimiter = ",", $replaceCharacter = ";")
{
    // open file in memory
    $csv = fopen('php://memory', 'w');

    // iterate through every row of the array
    foreach ($array as $row) {

        // replace every appearance of the delimiter in the raw data
        $row = str_replace($delimiter, $replaceCharacter, $row);
        // make csv
        fputcsv($csv, $row, $delimiter);
    }
    // set pointer to start of file
    fseek($csv, 0);
    // set download header
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $name . '";');
    // pass data to csv
    fpassthru($csv);
}


/**
 * Export a given array as a JSON file.
 *
 * Write a given array to a JSON file and set download headers.
 *
 * @param array $array Input array.
 * @param string $name Name of the JSON file.
 * @return JSON Output JSON file.
 */
function export_json($array, $name)
{

    // expects the first row to be header
    $header = $array[0];
    // list of all user emails
    $user = array();
    // array for ALL user data and challenge status
    $data = array();

    // iterate given array
    for ($i = 1; $i < count($array); $i++) {

        // add user to user array
        array_push($user, $array[$i][0]);

        // get every row without first element (email)
        $row = array_slice($array[$i], 1, count($array[$i]));

        // combine rows with header
        $assoc_row = array_combine(array_slice($header, 1, count($header)), $row);

        // add combined row to user data
        array_push($data, $assoc_row);
    }

    // combine user data with user email
    $result = array_combine($user, $data);

    // set download header
    header("Content-type: application/json");
    header("Content-disposition: attachment; filename=" . $name);

    // convert array to json
    return json_encode($result);;
}

/**
 * Get the size of a given file.
 *
 * Get the size of a give file in KB.
 *
 * @param string $file Path to input file.
 * @param string $unit Unit of for the file size.
 * @return int File size.
 * @throws Exception If file does not exits.
 */
function get_file_size($file, $unit = "kb")
{
    // check path to file
    if (file_exists($file)) {

        if (strtolower($unit) == "kb") {

            // return file size rounded to whole kilo bytes
            return round((filesize($file) / 1024), 0);
        } else if (strtolower($unit) == "mb") {

            // return file size rounded to whole mega bytes
            return round((filesize($file) / 1024 / 1024), 0);
        } else {

            // return file size in bytes
            return filesize($file);
        }
    } else {
        throw new Exception($file . " not found.");
    }
}

/**
 * Get the user name.
 *
 * Get the corresponding user name for a given mail address.
 *
 * @param string $mail User mail address.
 * @return string|bool User name.
 */
function get_user_name($mail)
{

    $sql = "SELECT `user_name` FROM `users` WHERE `user_wwu_email` = :mail";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([
            "mail" => $mail
        ]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        display_exception_msg($e, "128");
        exit();
    }

    return (!$result) ? false : $result['user_name'];
}

/**
 * Get challenge instructions.
 *
 * Load the challenge instructions for the global difficulty setting. The
 * supported instructions are 'general', 'XSS', 'SQLi' and 'CSRF'.
 *
 * @param string|array $instructionNames Name of the instruction section.
 * @throws Exception If function is called with wrong parameter data type.
 */
function get_challenge_instructions($instructionNames)
{

    try {
        // get instructions for single challenge
        if (gettype($instructionNames) == "string") {

            // make instruction name all lower case
            $instructionNames = strtolower($instructionNames);

            // load corresponding instruction file
            switch ($instructionNames) {
                case "general":
                    require(INST_GENERAL);
                    break;
                case "xss":
                    require(INST_XSS);
                    break;
                case "sqli":
                    require(INST_SQLI);
                    break;
                case "csrf":
                    require(INST_CSRF);
                    break;
                default:
                    // to catch typos
                    echo "Sorry, we could not find any instructions for the " .
                        "challenge: " . htmlspecialchars($instructionNames)
                        . ".";
            }
            // get instructions for multiple challenges
        } else if (gettype($instructionNames) == "array") {

            // get instructions for every array element
            foreach ($instructionNames as $name) {
                get_challenge_instructions($name);
            }
        } else {

            // unsupported data type
            $msg = "You've used an unsupported data type for the input "
                . "parameter of <b>get_challenge_instructions</b>. Your "
                . "parameter '" . htmlspecialchars($instructionNames)
                . "' is of type " . gettype($instructionNames) . ".";
            throw new Exception($msg);
        }
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Wrapper function to send a mail via php mail function.
 *
 * @param string $to Receiver, or receivers of the mail.
 * @param string $subject Subject of the email to be sent.
 * @param string $message Message to be sent.
 * @param string|array $header String or array to be inserted at the end of the
 * email header. Since 7.2.0 accepts an array. Its keys are the header names and
 * its values are the respective header values.
 * @return bool True if the mail was successfully accepted for delivery, false
 * otherwise.
 */
function send_mail($to, $subject, $message, $header)
{
    $mailStatus = mail($to, $subject, $message, $header);
    return $mailStatus;
}
