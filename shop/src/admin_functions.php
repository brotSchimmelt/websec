<?php



function get_num_of_students()
{
    $sql = "SELECT `user_name` FROM `users`";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

function get_num_of_unlocked_students()
{
    $sql = "SELECT `is_unlocked` FROM `users` WHERE `is_unlocked` = '1'";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

function get_num_of_admins()
{
    $sql = "SELECT `is_admin` FROM `users` WHERE `is_admin` = '1'";
    $stmt = get_login_db()->query($sql);
    return $stmt->rowCount();
}

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

function get_individual_progress($username)
{
    $xssStatus = check_xss_challenge($username);
    $sqliStatus = check_sqli_challenge($username);
    $csrfStatus = check_crosspost_challenge($username);
    $csrfStatusReferrer = check_crosspost_challenge_double($username);

    // integer from 0 to 4 indicating the overall success
    $totalStatus = 0; // 0 means nothing was accomplished
    $totalStatus = $xssStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $sqliStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $csrfStatus ? ++$totalStatus : $totalStatus;
    $totalStatus = $csrfStatusReferrer ? ++$totalStatus : $totalStatus;

    return $totalStatus;
}

function show_students_with_open_challenges()
{
    $sql = "SELECT `user_name`, `user_wwu_email`, `is_unlocked`, `is_admin`, `timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        // check if student did already all the challenges
        if (get_individual_progress($row['user_name']) == 4) { // 4 is the number of challenges
            continue;
        }

        // get every open challenge
        $status = array();

        if (!check_xss_challenge($row['user_name'])) {
            array_push($status, "XSS");
        }

        if (!check_sqli_challenge($row['user_name'])) {
            array_push($status, "SQLi");
        }

        if (!check_crosspost_challenge($row['user_name'] and !check_crosspost_challenge_double($row['user_name']))) {
            array_push($status, "Crosspost");
        } else if (check_crosspost_challenge($row['user_name']) and !check_crosspost_challenge_double($row['user_name'])) {
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
        echo "<td>" . '<a href="mailto:' . $row['user_wwu_email'] . '">' . $row['user_wwu_email'] . '</a></td>';
        echo "<td>" . $openChallenges . "</td>";
        echo "<td>" . $adminFlag . "</td>";
        echo "<td>" . $unlockedFlag . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "</tr>";

        $pos++;
    }
}



function show_all_user()
{
    $sql = "SELECT `user_id`, `user_name`, `user_wwu_email`, `is_unlocked`, `is_admin`, `timestamp` FROM users";
    $stmt = get_login_db()->query($sql);

    $pos = 1;
    while ($row = $stmt->fetch()) {

        $editBtn = '<button class="btn btn-sm btn-info mr-2" id="' . $row['user_name'] . '-edit">Edit</button>';
        $resetPwdBtn = '<button class="btn btn-sm btn-info mr-2" id="' . $row['user_name'] . '-pwd">Reset Password</button>';
        $deleteBtn = '<button class="btn btn-sm btn-danger" id="' . $row['user_name'] . '-delete">Delete</button>';
        $adminBtn = '<button class="btn btn-sm btn-info mr-2" id="' . $row['user_name'] . '-admin">Make Admin</button>';


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
        echo "<td>" . $editBtn . $adminBtn . $resetPwdBtn . $deleteBtn . "</td>";
        echo "</tr>";

        $pos++;
    }
}
