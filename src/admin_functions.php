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

// get the number of students from the login database
function get_num_of_students()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfStudents FROM `users` "
        . "WHERE `is_admin` = 0";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfStudents'];
}

// get number of unlocked students from the login database
function get_num_of_unlocked_students()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfUnlocked FROM `users` "
        . "WHERE `is_unlocked` = 1";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfUnlocked'];
}

// get the number of admin users from the login database
function get_num_of_admins()
{
    $sql = "SELECT COUNT(`user_name`) AS numberOfAdmins FROM `users` "
        . "WHERE `is_admin` = 1";
    $stmt = get_login_db()->query($sql);

    return $stmt->fetch()['numberOfAdmins'];
}

// check if a user is unlocked in the database
function is_user_unlocked_in_db($username)
{
    $sql = "SELECT `is_unlocked` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    return ($result['is_unlocked'] == 1) ? true : false;
}

// check if a user is an admin
function is_user_admin_in_db($username)
{
    $sql = "SELECT `is_admin` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    return ($result['is_admin'] == 1) ? true : false;
}

// get the challenge progress of all students in the database
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

// get challenge progress of one student
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

// get all students with at least one open challenge
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

// show all challenge statuses for the students
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

// get all solved challenges for a user
function get_solved_challenges($username)
{
    // initialize array of solved challenges
    $challenges = array();

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

    return implode(", ", $challenges);
}

// get all not yet solved challenges for a user
function get_open_challenges($username)
{
    // get every open challenge
    $challenges = array();

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

// get CSRF referer and messages for a user
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

// set new global difficulty level in settings.php
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

// set login status
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

// set registration status
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

// get the results of all users as JSON
function get_results_as_json()
{
    $sql = "SELECT `user_name`, `user_wwu_email`, `is_admin` FROM `users`";
    $stmt = get_login_db()->query($sql);

    // initialize JSON
    $json = array();

    while ($row = $stmt->fetch()) {

        // exclude admin users
        if (is_user_admin_in_db($row['user_name'])) {
            continue;
        }

        // get results
        $solvedChallenges = get_solved_challenges($row['user_name']);
        $CSRFResults = get_csrf_challenge_data($row['user_name']);

        // make JSON file
        $json[$row['user_wwu_email']]['username'] = $row['user_name'];
        $json[$row['user_wwu_email']]['solved_challenges'] = $solvedChallenges;
        $json[$row['user_wwu_email']]['csrf_referrer'] = $CSRFResults['referrer'];
        $json[$row['user_wwu_email']]['csrf_message'] = $CSRFResults['message'];
        $json[$row['user_wwu_email']]['difficulty'] = get_global_difficulty();
    }

    return json_encode($json);
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
