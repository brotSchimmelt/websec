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
    $sql = "SELECT `user_name` FROM `users`";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

// get number of unlocked students from the login database
function get_num_of_unlocked_students()
{
    $sql = "SELECT `is_unlocked` FROM `users` WHERE `is_unlocked` = '1'";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

// get the number of admin users from the login database
function get_num_of_admins()
{
    $sql = "SELECT `is_admin` FROM `users` WHERE `is_admin` = '1'";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

// check if a user is unlocked in the database
function is_user_unlocked_in_db($username)
{
    $sql = "SELECT `is_unlocked` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    if ($result['is_unlocked'] == 1) {
        return true;
    } else {
        return false;
    }
}

// check if a user is an admin
function is_user_admin_in_db($username)
{
    $sql = "SELECT `is_admin` FROM `users` WHERE `user_name` = :user_name";
    $stmt = get_login_db()->prepare($sql);
    $stmt->execute(['user_name' => $username]);
    $result = $stmt->fetch();

    if ($result['is_admin'] == 1) {
        return true;
    } else {
        return false;
    }
}

// get the challenge progress of all students in the database
function get_total_progress($numOfStudents, $numOfChallenges)
{
    $sql = "SELECT `user_name` FROM `users`";
    $stmt = get_login_db()->query($sql);

    $absoluteProgress = 0;
    while ($row = $stmt->fetch()) {
        $absoluteProgress += get_individual_progress($row['user_name']);
    }

    // calculate overall percentage
    $maxPoints = $numOfChallenges * $numOfStudents;
    $totalProgress = $absoluteProgress / $maxPoints * 100;

    return round($totalProgress, 2);
}

// get challenge progress of one student
function get_individual_progress($username)
{
    $xssStatus = lookup_challenge_status("reflective_xss", $username);
    $sqliStatus = lookup_challenge_status("sqli", $username);
    $csrfStatus = lookup_challenge_status("csrf", $username);
    $csrfStatusReferrer = lookup_challenge_status("csrf_referrer", $username);

    // integer from 0 to 4 indicating the overall success
    $totalStatus = 0; // 0 means nothing was accomplished
    $totalStatus = $xssStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $sqliStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $csrfStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $csrfStatusReferrer ? ++$totalStatus : $totalStatus;

    return $totalStatus;
}

// get all students with at least one open challenge
function show_students_with_open_challenges()
{
    $sql = "SELECT `user_name`, `user_wwu_email`, `is_unlocked`, `is_admin`, "
        . "`timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        // check if student did already all the challenges
        // 4 is the current number of challenges
        if (get_individual_progress($row['user_name']) == 4) {
            continue;
        }

        // get every open challenge
        $status = array();

        if (!lookup_challenge_status("reflective_xss", $row['user_name'])) {
            array_push($status, "XSS");
        }

        if (!lookup_challenge_status("sqli", $row['user_name'])) {
            array_push($status, "SQLi");
        }

        if (
            !lookup_challenge_status("csrf", $row['user_name'])
            and !lookup_challenge_status("csrf_referrer", $row['user_name'])
        ) {

            array_push($status, "Crosspost");
        } else if (
            lookup_challenge_status("csrf", $row['user_name']) and
            !lookup_challenge_status("csrf_referrer", $row['user_name'])
        ) {

            array_push($status, "Crosspost*");
        }

        // build output string
        $openChallenges = implode(", ", $status);

        // make table row entry
        $adminClass = $row['is_admin'] == 1 ? "is-admin" : "";
        $adminFlag = $row['is_admin'] == 1 ? "Yes" : "No";
        $unlockedFlag = $row['is_unlocked'] == 1 ? "Yes" : "No";
        echo '<tr class="' . $adminClass . '">';
        echo "<td><strong>" . $pos . ".</strong></td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . '<a href="mailto:' . $row['user_wwu_email'] . '">'
            . $row['user_wwu_email'] . '</a></td>';
        echo "<td>" . $openChallenges . "</td>";
        echo "<td>" . $adminFlag . "</td>";
        echo "<td>" . $unlockedFlag . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "</tr>";

        $pos++;
    }
}

// get all users from the database
function show_all_user()
{
    $sql = "SELECT `user_id`, `user_name`, `user_wwu_email`, `is_unlocked`, "
        . "`is_admin`, `timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        $editBtn = '<button class="btn btn-sm btn-info mr-2" id="'
            . $row['user_name'] . '-edit">Edit</button>';
        $deleteBtn = '<button class="btn btn-sm btn-danger" id="'
            . $row['user_name'] . '-delete">Delete</button>';


        $adminFlag = $row['is_admin'] == 1 ? "Yes" : "No";
        $unlockedFlag = $row['is_unlocked'] == 1 ? "Yes" : "No";
        echo "<tr>";
        echo "<td><strong>" . $pos . ".</strong></td>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . $row['user_wwu_email'] . "</td>";
        echo "<td>" . $adminFlag . "</td>";
        echo "<td>" . $unlockedFlag . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "<td>" . $editBtn . $deleteBtn . "</td>";
        echo "</tr>";

        $pos++;
    }
}

// set new global difficulty level in settings.php
function set_global_difficulty($difficulty)
{
    // load settings.json path from config.php
    $file = SETTINGS;
    if (file_exists($file)) {

        // load settings as assoc array
        $settings = json_decode(file_get_contents($file), true);

        // translate parameter to difficulty level
        $settings['difficulty']['hard'] = ($difficulty == "hard") ? true : false;

        // write new settings file in place
        file_put_contents($file, json_encode($settings));
    } else {
        throw new Exception("Settings.json could not be opened or found.");
    }
}
