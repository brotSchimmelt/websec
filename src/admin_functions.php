<?php
/* 
* PDO Exceptions are not caught in this sections since only admin users should
* have access.
* Furthermore, the default PDO Exceptions are exceptional (pun intended) helpful
* in regards to debugging.
* They are only caught in the sections accessible by normal users to provide
* extra information to the students that this is indeed an error, that has no 
* connection to the hacking challenges and should be reported.
*/

/**
 * Get the number of students from the login database.
 * 
 * Uses the MySQL COUNT function to get the number of all non-admin users in 
 * the 'users' table.
 *
 * @return integer Indicates the number of students.
 */
function get_num_of_students()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfStudents FROM `users` "
        . "WHERE `is_admin` = 0";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfStudents'];
}

/**
 * Get number of unlocked students from the login database.
 * 
 * Uses the MySQL COUNT function to get the number of all non-admin users 
 * that are unlocked in the 'users' table.
 * 
 * @return integer Indicates the number of unlocked students.
 */
function get_num_of_unlocked_students()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfUnlocked FROM `users` "
        . "WHERE `is_unlocked` = 1 AND `is_admin` = 0";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfUnlocked'];
}

/**
 * Get the number of admin users from the login database.
 * 
 * Uses the MySQL COUNT function to get the number of all admin users from the 
 * 'users' table.
 * 
 * @return integer Indicates the number of admins.
 */
function get_num_of_admins()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfAdmins FROM `users` "
        . "WHERE `is_admin` = 1";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfAdmins'];
}

// check if a user is unlocked in the database

/**
 * Check if a user is unlocked in the login database.
 * 
 * Check the 'users' table if a given user is already unlocked.
 *
 * @param string $username Given username.
 * @return bool Unlock status. 
 * 
 */
function is_user_unlocked_in_db($username)
{
    $sql = "SELECT `is_unlocked` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    return ($result['is_unlocked'] == 1) ? true : false;
}

/**
 * Check if a given user is an admin user in the login database.
 * 
 * Check in the 'users' table if a give user is an admin.
 * 
 * @param string $username Given username.
 * @return bool Admin status.
 */
function is_user_admin_in_db($username)
{
    $sql = "SELECT `is_admin` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    return ($result['is_admin'] == 1) ? true : false;
}

/**
 * Get the challenge progress of all students as percentage.
 * 
 * Get the overall challenge progress of all non-admin users as percentage. The 
 * value is calculated with the constant NUM_CHALLENGES (number of challenges).
 * 
 * @return integer|float Progress percentage.
 */
function get_total_progress()
{

    $numOfStudents = get_num_of_students();
    $numOfChallenges = NUM_CHALLENGES; // hardcoded in config.php
    // calculate overall percentage
    $maxPoints = $numOfChallenges * $numOfStudents;

    $sql = "SELECT `user_name` FROM `users` WHERE `is_admin` = 0";
    $stmt = get_login_db()->query($sql);

    $absoluteProgress = 0;
    while ($row = $stmt->fetch()) {
        $absoluteProgress += get_individual_progress($row['user_name']);
    }

    // check if there is at least 1 normal (non-admin) user
    $totalProgress = ($numOfStudents == 0) ? 0 :
        $absoluteProgress / $maxPoints * 100;

    return round($totalProgress, 2);
}

/**
 * Get the challenge progress of a given user.
 * 
 * Get the cumulative challenge progress of a given user as an integer value.
 * 
 * @param string $username Given user.
 * @return integer Progress as integer from 0 - 4.
 */
function get_individual_progress($username)
{
    $xssReflectiveStatus = lookup_challenge_status("reflective_xss", $username);
    $xssStoredStatus = lookup_challenge_status("stored_xss", $username);
    $sqliStatus = lookup_challenge_status("sqli", $username);
    $csrfStatus = lookup_challenge_status("csrf", $username);

    // integer from 0 to 4 indicating the overall success
    $totalStatus = 0; // 0 means nothing was accomplished yet
    $totalStatus = $xssReflectiveStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $xssStoredStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $sqliStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $csrfStatus ? ++$totalStatus : $totalStatus;

    return $totalStatus;
}

/**
 * Show all students with at least 1 open challenge.
 * 
 * Print the open challenges, admin flag, unlocked flag and last activity for 
 * every student as table row to the screen.
 */
function show_students_with_open_challenges()
{
    $sql = "SELECT `user_name`, `user_wwu_email`, `is_unlocked`, `is_admin`, "
        . "`last_login`, `timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        // check if student did already all the challenges
        // 5 is the current number of challenges
        if (get_individual_progress($row['user_name']) == 4) {
            continue;
        }

        $openChallenges = get_open_challenges($row['user_name']);

        // format row entries
        $adminClass = $row['is_admin'] == 1 ? "is-admin" : "";
        $adminFlag = $row['is_admin'] == 1 ?
            '<span style="color:orange">Yes</span>' :
            '<span style="color:green">No</span>';
        $unlockedFlag = $row['is_unlocked'] == 1 ?
            '<span style="color:green">Yes</span>' :
            '<span style="color:red">No</span>';
        $lastActivity = (!empty($row['last_login'])) ? $row['last_login'] :
            $row['timestamp'];

        // echo table content
        echo '<tr class="' . $adminClass . '">';
        echo "<td><strong>" . $pos . ".</strong></td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . '<a href="mailto:' . $row['user_wwu_email'] . '">'
            . $row['user_wwu_email'] . '</a></td>';
        echo "<td>" . $openChallenges . "</td>";
        echo "<td>" . $adminFlag . "</td>";
        echo "<td>" . $unlockedFlag . "</td>";
        echo "<td>" . $lastActivity . "</td>";
        echo "</tr>";

        $pos++;
    }
}

/**
 * Show all solved challenges for all students.
 * 
 * Print the solved challenges, the CSRF referrer + message and the current 
 * global difficulty to the screen.
 */
function show_solved_challenges()
{
    $sql = "SELECT `user_name`, `user_wwu_email`, `is_admin`, `last_login`, "
        . "`timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        // exclude admin users
        if (is_user_admin_in_db($row['user_name'])) {
            continue;
        }

        $solvedChallenges = get_solved_challenges($row['user_name']);

        $CSRFResults = get_csrf_challenge_data($row['user_name']);

        // make table row entry
        echo "<tr>";
        echo "<td><strong>" . $pos . ".</strong></td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" .  $row['user_wwu_email'] . "</td>";
        echo "<td>" . $solvedChallenges . "</td>";
        echo "<td>" . $CSRFResults['referrer'] . "</td>";
        echo "<td>" . $CSRFResults['message'] . "</td>";
        echo "<td>" . get_global_difficulty()  . "</td>";
        echo "</tr>";

        $pos++;
    }
}

/**
 * Get the names of all solved challenges for a given user.
 * 
 * Iterates through all challenges for a given user and returns the name of the
 * challenge, if it's been solved.
 * 
 * @param string $username Given user name. 
 * @return string A list of all solved challenges. 
 */
function get_solved_challenges($username)
{
    // initialize array of solved challenges
    $challenges = array();

    // check all challenges
    if (lookup_challenge_status("reflective_xss", $username)) {
        array_push($challenges, "Reflective XSS");
    }

    if (lookup_challenge_status("stored_xss", $username)) {
        array_push($challenges, "Stored XSS");
    }

    if (lookup_challenge_status("sqli", $username)) {
        array_push($challenges, "SQLi");
    }

    if (
        lookup_challenge_status("csrf", $username)
        and lookup_challenge_status("csrf_referrer", $username)
    ) {
        array_push($challenges, "Crosspost");
    } else if (
        lookup_challenge_status("csrf", $username) and
        !lookup_challenge_status("csrf_referrer", $username)
    ) {
        array_push($challenges, "Crosspost*");
    }

    // format array
    if (empty($challenges)) {
        array_push($challenges, "-");
    }

    // return list of solved challenges as string
    return implode(", ", $challenges);
}

/**
 * Get the names of all open challenges for a given user.
 * 
 * Iterates through all challenges for a given user and returns the name of the
 * challenge, if it's NOT been solved yet.
 * 
 * @param string $username Given user name. 
 * @return string A list of all open challenges. 
 */
function get_open_challenges($username)
{
    // get every open challenge
    $challenges = array();

    // check all challenges
    if (!lookup_challenge_status("reflective_xss", $username)) {
        array_push($challenges, "Reflective XSS");
    }

    if (!lookup_challenge_status("stored_xss", $username)) {
        array_push($challenges, "Stored XSS");
    }

    if (!lookup_challenge_status("sqli", $username)) {
        array_push($challenges, "SQLi");
    }

    if (
        !lookup_challenge_status("csrf", $username)
        and !lookup_challenge_status("csrf_referrer", $username)
    ) {

        array_push($challenges, "Crosspost");
    } else if (
        lookup_challenge_status("csrf", $username) and
        !lookup_challenge_status("csrf_referrer", $username)
    ) {

        array_push($challenges, "Crosspost*");
    }

    // build output string
    return implode(", ", $challenges);
}

/**
 * Get CSRF referrer and messages for a given user.
 * 
 * Get the referrer and message for a given user, if the CSRF challenge has been
 * solved. Otherwise return a '-' to indicate that the challenge is still open.
 * 
 * @param string $username Given user name. 
 * @return array Assoc array with message and referrer as elements. 
 */
function get_csrf_challenge_data($username)
{
    // get referrer and message
    $CSRFSQL = "SELECT `referrer`,`message` FROM `csrf_posts` WHERE "
        . "`user_name`=?";
    $CSRFStmt = get_shop_db()->prepare($CSRFSQL);
    $CSRFStmt->execute([$username]);
    $CSRFResult = $CSRFStmt->fetch();

    // format referrer and message
    if (!$CSRFResult) {
        $result['referrer'] = "-";
        $result['message'] = "-";
    } else {
        $referrerURL = parse_url($CSRFResult['referrer']);
        $result['referrer'] = $referrerURL['path']; // shorten referrer
        $result['message'] = $CSRFResult['message'];
    }

    return $result;
}

/**
 * Set new global difficulty level.
 * 
 * Set a new global difficulty level in the settings.json file.
 * 
 * @param string $difficulty New difficulty level.
 */
function set_global_difficulty($difficulty)
{
    // translate input to valid setting value
    $newValue = ($difficulty == "hard") ? true : false;

    try {
        // set new value
        set_setting('difficulty', 'hard', $newValue);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Set the login form status.
 * 
 * Enable (true) or disable (false) the login function in the settings.json
 * file.
 * 
 * @param bool $status Value to enable or disable the login function.
 */
function set_login_status($status)
{

    // translate input to valid setting value
    $newValue = !$status;

    try {
        // set new value
        set_setting('login', 'disabled', $newValue);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Set the registration form status.
 * 
 * Enable (true) or disable (false) the registration function in the 
 * settings.json file.
 * 
 * @param bool $status Value to enable or disable the registration function.
 */
function set_registration_status($status)
{

    // translate input to valid setting value
    $newValue = !$status;

    try {
        // set new value
        set_setting('registration', 'disabled', $newValue);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

/**
 * Get the results of all students as assoc array.
 * 
 * Get the personal data and challenge results of all non-admin users in an 
 * assoc array with a header.
 *
 * @return array Assoc array with all student data. 
 */
function get_results_as_array()
{
    $sql = "SELECT `user_name`, `user_wwu_email` FROM `users` WHERE "
        . "`is_admin` = 0";
    $stmt = get_login_db()->query($sql);

    // initialize result array with header
    $results = array(array(
        "wwu_mail", "user_name", "difficulty",
        "reflective_xss", "stored_xss", "sqli", "csrf", "csrf_referrer_match",
        "reflective_xss_solution", "stored_xss_solution", "sqli_solution",
        "csrf_solution", "csrf_referrer", "csrf_msg"
    ));

    // save each user as a line
    while ($row = $stmt->fetch()) {

        // get mail, name and difficulty
        $line = array(
            $row['user_wwu_email'],
            $row['user_name'],
            get_global_difficulty()
        );

        // add challenge status to user line
        $tmp = array_merge($line, get_challenge_status($row['user_name']));

        // get challenge solutions
        if (lookup_challenge_status("reflective_xss", $row['user_name'])) {
            $reflectiveXSS = get_challenge_solution(
                $row['user_name'],
                "reflective_xss"
            );
        } else {
            $reflectiveXSS = "-";
        }
        if (lookup_challenge_status("stored_xss", $row['user_name'])) {
            $storedXSS = get_challenge_solution(
                $row['user_name'],
                "stored_xss"
            );
        } else {
            $storedXSS = "-";
        }
        if (lookup_challenge_status("sqli", $row['user_name'])) {
            $sqli = get_challenge_solution(
                $row['user_name'],
                "sqli"
            );
        } else {
            $sqli = "-";
        }
        if (lookup_challenge_status("csrf", $row['user_name'])) {
            $csrf = get_last_challenge_input($row['user_name'], "csrf");
            $csrf_referrer =
                get_last_challenge_input($row['user_name'], "csrf_referrer");
            $csrf_msg = get_last_challenge_input($row['user_name'], "csrf_msg");
        } else {
            $csrf = "-";
            $csrf_referrer = "-";
            $csrf_msg = "-";
        }

        // add solutions to user line
        array_push(
            $tmp,
            $reflectiveXSS,
            $storedXSS,
            $sqli,
            $csrf,
            $csrf_referrer,
            $csrf_msg
        );

        // add user line to results array
        array_push($results, $tmp);
    }

    return $results;
}

/**
 * Get all challenge status for a given user.
 * 
 * Get all challenge status for a given user as either 0 or 1.
 */
function get_challenge_status($username)
{
    // get challenge data for user
    $sql = "SELECT * FROM `challengeStatus` WHERE `user_name` = ?";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute([$username]);
    $result = $stmt->fetch();

    $challengeStatus = array();

    if (get_global_difficulty() != "hard") {
        // add status to array
        array_push($challengeStatus, $result['reflective_xss']);
        array_push($challengeStatus, $result['stored_xss']);
        array_push($challengeStatus, $result['sqli']);
        array_push($challengeStatus, $result['csrf']);
        array_push($challengeStatus, $result['csrf_referrer']);
    } else {
        // add status to array
        array_push($challengeStatus, $result['reflective_xss_hard']);
        array_push($challengeStatus, $result['stored_xss_hard']);
        array_push($challengeStatus, $result['sqli_hard']);
        array_push($challengeStatus, $result['csrf_hard']);
        array_push($challengeStatus, $result['csrf_referrer_hard']);
    }

    return $challengeStatus;
}

// set new blocked usernames list in settings.json
function set_blocked_usernames($list)
{
    try {
        // set new value
        set_setting('usernames', 'deny_list', $list);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// set new allowed domains list in settings.json
function set_allowed_domains($list)
{
    try {
        // set new value
        set_setting('domains', 'allow_list', $list);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// set new badge link for a challenge
function set_badge_link($challenge, $link)
{
    try {
        // set new value
        set_setting('badge_links', $challenge, $link);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }
}

// display the solutions of the challenge for all non-admin users
function show_challenge_solutions()
{

    // get all non-admin results
    $results = get_results_as_array();

    // store header separately
    $header = $results[0];

    // remove header from results
    array_shift($results);

    // iterate over all users (rows)
    foreach ($results as $row) {

        $userResults = array_combine($header, $row);

        echo "<tr>";
        echo "<td>" . $userResults['user_name'] . "</td>";
        echo "<td>" . htmlspecialchars($userResults['reflective_xss_solution'])  . "</td>";
        echo "<td>" . htmlspecialchars($userResults['stored_xss_solution']) . "</td>";
        echo "<td>" . htmlspecialchars($userResults['sqli_solution']) . "</td>";
        echo "<td>" . htmlspecialchars($userResults['csrf_solution']) . "</td>";
        echo "</tr>";
    }
}


// display all challenge input file sizes
function show_file_sizes()
{

    // get list of all users
    $sql = "SELECT `user_name` FROM `users`";
    $stmt = get_login_db()->query($sql);

    $users = $stmt->fetchAll();

    $pos = 1;
    foreach ($users as $user) {

        $file = DAT . slug($user['user_name']) . ".json";

        // skip users that haven't started yet
        if (!file_exists($file)) {
            continue;
        }

        $size = get_file_size($file, $unit = "kb");

        // shorten file path
        $path = str_replace("public/../", "", $file);
        $path = str_replace("/var/www/html", "", $path);

        echo "<tr>";
        echo "<td><strong>" . $pos . "</strong>.</td>";
        echo "<td>" . $user['user_name'] . "</td>";
        echo "<td>" . $path . "</td>";
        if ($size >= 10) {
            echo '<td><strong class="text-warning">' . $size . "</strong> KB</td>";
        } else {
            echo "<td><strong>" . $size . "</strong> KB</td>";
        }
        echo "</tr>";
        $pos++;
    }
}
