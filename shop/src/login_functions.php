<?php

/**
 * Get the PDO connection for the login DB.
 * 
 * Uses the credentials defined in the config.php file.
 *
 * @return \PDO the login database connection.
 */
function get_login_db()
{
    // ensure only one connection is alive
    static $dbLogin;
    if ($dbLogin instanceof PDO) {
        return $dbLogin;
    }

    try {
        $dbLogin = new PDO(DSN_LOGIN, DB_USER_LOGIN, DB_PWD_LOGIN, OPTIONS_LOGIN);
    } catch (PDOException $e) {
        $note = "The connection to our database could not be established. "
            . 'If this error persists, please post it to the '
            . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
            . 'target="_blank">Learnweb</a> forum.';
        display_exception_msg(null, "010", $note);
        exit();
    }
    return $dbLogin;
}

// check if a POST variable is set and not empty
function post_var_set($varName)
{
    if (isset($_POST[$varName]) && !empty($_POST[$varName])) {
        return true;
    } else {
        return false;
    }
}

// check if a GET variable is set and not empty
function get_var_set($varName)
{
    if (isset($_GET[$varName]) && !empty($_GET[$varName])) {
        return true;
    } else {
        return false;
    }
}

// check if a username exists at most 1 time in the database
function check_user_exists($numUsers)
{
    if ($numUsers > 1) {

        sleep(3); // 3 seconds
        // check if there is more than 1 entry for that name
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=041");
        exit();
    } else if ($numUsers < 1) {

        sleep(3); // 3 seconds
        // user not found
        header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
        exit();
    } else {
        return true;
    }
}

// check that the entered password matches the hash in the database
function verify_pwd($pwd, $resultArray, $redirect = LOGIN_PAGE)
{
    $pwdTest = password_verify($pwd, $resultArray['user_pwd_hash']);
    if ($pwdTest) {
        return true;
    } else if (!$pwdTest) {

        sleep(3); // 3 seconds
        // send user back if password does not match
        header("location: " . $redirect . "?error=wrongCredentials");
        exit();
    } else {

        sleep(3); // 3 second
        // just to catch any errors in the 'password_verify' function
        header("location: " . $redirect . "?error=internalError");
        exit();
    }
}

// check if username is in scope and 2 <= len <= 64
function validate_username($username)
{
    if (!preg_match("/^[A-Za-z0-9]*$/", $username)) {
        return false;
    } else if ((mb_strlen($username) > 64) || (mb_strlen($username) < 2)) {
        return false;
    }
    return true;
}

// check if entered user e-mail is a valid address
function validate_mail($mail)
{
    // check if user input is a valid mail format
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        header("location: " . REGISTER_PAGE . "?error=invalidMailFormat");
        exit();
    }

    // Note: Add other valid domains here!
    $validDomains = array(
        "@uni-muenster.de", "@wi.uni-muenster.de",
        "@gmail.com"
    );

    // check if a valid domain us used
    $needle = mb_strstr($mail, "@");
    if (in_array($needle, $validDomains)) {
        return true;
    } else {
        return false;
    }
}

// check if the user password matches the password criteria
function validate_pwd($pwd)
{
    if (!$pwd || mb_strlen($pwd) < 8) {
        return false;
    }
    return true;
}

// hash the user password with the php default method
function hash_user_pwd($pwd)
{
    $hash = password_hash($pwd, PASSWORD_DEFAULT, ['cost' => 13]);
    // check if the hash was successfully created
    if (!$hash) {
        throw new Exception("Hash creation failed.");
    }
    return $hash;
}

// check if the give username already exists in the database
function check_entry_exists($entry, $sql)
{
    // Fake user array
    $fakeUser = array("admin", "elliot", "l337_h4ck3r");

    // Get entries from DB
    try {
        $duplicateQuery = get_login_db()->prepare($sql);
        $duplicateQuery->execute([$entry]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=0102");
        exit();
    }

    $entryExists = $duplicateQuery->fetchColumn();
    if ($entryExists) {
        return true;
    } else {

        // Check fake users
        if (in_array($entry, $fakeUser)) {
            return true;
        }
        return false;
    }
}

// check all user input for registration process
function validate_registration_input($username, $mail, $password, $confirmPassword)
{
    // check if username AND mail are okay
    if (!validate_username($username) && !validate_mail($mail)) {
        header("location: " . REGISTER_PAGE . "?error=invalidNameAndMail");
        exit();
    }
    // validate the username alone
    else if (!validate_username($username)) {
        $oldInput = "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=invalidUsername" . $oldInput);
        exit();
    }
    // validate the mail adress alone
    else if (!validate_mail($mail)) {
        $oldInput = "&username=" . $username;
        header("location: " . REGISTER_PAGE . "?error=invalidMail" . $oldInput);
        exit();
    }
    // validate the password
    else if (!validate_pwd($password)) {
        $oldInput = "&username=" . $username . "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=invalidPassword" . $oldInput);
        exit();
    }
    // check if the passwords match
    else if ($password !== $confirmPassword) {
        $oldInput = "&username=" . $username . "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=passwordMismatch" . $oldInput);
        exit();
    } else {
        // all checks passed!
        return true;
    }
}

// log user in
function do_login($username, $mail, $adminFlag, $unlockedFlag)
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session has not yet started
        session_start();
    }

    try {
        $token = get_random_token(32);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    $_SESSION['userToken'] = $token;
    $_SESSION['userName'] = $username;
    $_SESSION['userMail'] = $mail;
    $_SESSION['userLoginStatus'] = 1;

    if ($adminFlag == 1) {
        $_SESSION['userIsAdmin'] = $adminFlag;
    }

    if ($unlockedFlag == 1) {
        $_SESSION['userIsUnlocked'] = $unlockedFlag;
    }

    update_last_login($username);
}

// validate all user input for login
function try_login($username, $pwd)
{
    // Get pwd and username from DB
    $sql = "SELECT user_name,user_pwd_hash,user_wwu_email,is_admin,is_unlocked ";
    $sql .= "FROM users WHERE user_name=?";
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
    } catch (PDOException $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=103");
        exit();
    }

    // check if user exists
    $numUsers = $stmt->rowCount();
    if (check_user_exists($numUsers)) {

        // validate the entered password
        $result = $stmt->fetch();
        if (verify_pwd($pwd, $result)) {

            do_login(
                $result['user_name'],
                $result['user_wwu_email'],
                $result['is_admin'],
                $result['is_unlocked']
            );
            header("location: " . MAIN_PAGE . "?success=login");
            exit();
        }
    }
}

// check if user or password already exist in the database
function try_registration($username, $mail, $password)
{
    $usernameSQL = "SELECT 1 FROM users WHERE user_name = ?";
    $mailSQL = "SELECT 1 FROM users WHERE user_wwu_email = ?";

    // check if username or mail already exits in the db
    if (check_entry_exists($username, $usernameSQL)) {

        $oldInput = "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=nameError" . $oldInput);
        exit();
    } else if (check_entry_exists($mail, $mailSQL)) {

        $oldInput = "&username=" . $username;
        header("location: " . REGISTER_PAGE . "?error=mailTaken" . $oldInput);
        exit();
    } else {
        do_registration($username, $mail, $password);
    }
}

// register user
function do_registration($username, $mail, $password)
{
    try {
        $pwdHash = hash_user_pwd($password);
    } catch (Exception $e) {
        display_exception_msg($e, "031");
        exit();
    }

    try {
        $XSSChallengeCookie = get_random_token(16);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    $insertUser = "INSERT INTO users (user_id, user_name, "
        . "user_wwu_email, user_pwd_hash, is_unlocked, is_admin, timestamp, "
        . " last_login) VALUE (NULL, :user, :mail, :pwd_hash, '0', "
        . "'0', :timestamp, NULL)";

    try {
        get_login_db()->prepare($insertUser)->execute([
            'user' => $username,
            'mail' => $mail,
            'pwd_hash' => $pwdHash,
            'timestamp' => date("Y-m-d H:i:s")
        ]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=104");
        exit();
    }

    $insertCookie = "INSERT INTO fakeCookie (id, user_name, "
        . "reflective_xss) VALUE (NULL, :user, :cookie)";

    try {
        get_login_db()->prepare($insertCookie)->execute([
            'user' => $username,
            'cookie' => $XSSChallengeCookie
        ]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=119");
        exit();
    }

    try {
        // Create personal DB for SQLi challenge
        create_sqli_db($username, $mail);
    } catch (Exception $e) {
        display_exception_msg($e, "051");
        exit();
    }


    // redirect back to login page
    header("location: " . LOGIN_PAGE . "?success=signup");
    exit();
}

// generate a custom random token
function get_random_token($length)
{
    if ($length <= 0) {
        trigger_error("Code Error: Token length cannot be 0 or negative!");
    }

    $token = bin2hex(openssl_random_pseudo_bytes($length));
    if ($token === false) {
        throw new Exception("Token creation failed.");
    }

    // trim the new token to the predefined length
    return substr($token, 0, $length);
}

// create a reset request for the user password
function do_pwd_reset($mail)
{
    try {
        $selector = get_random_token(16); // to select user from the database
        $validator = get_random_token(32); // to validate password reset request
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    $expires = date('U') + 1200; // Token expires after 20 min (1200 s)

    // URL for redirect to password reset page
    $url = SITE_URL . "/create_new_password.php?s="
        . $selector . "&v=" . $validator;

    // Check if an old entry already exists for this mail and delete it
    if (check_pwd_request_status($mail)) {
        delete_pwd_request($mail);
    }

    // Add request to DB
    add_pwd_request($mail, $selector, $validator, $expires);

    // Send mail with reset instructions to user
    send_pwd_reset_mail($mail, $url);

    // Show success message
    header("location: " . LOGIN_PAGE .  "?success=requestProcessed");
    exit();
}

// check if a request in the database exists for a given mail address
function check_pwd_request_status($mail)
{
    $sql = "SELECT * FROM `resetPwd` WHERE `user_wwu_email`=?";

    try {
        $query = get_login_db()->prepare($sql);
        $query->execute([$mail]);
    } catch (PDOException $e) {
        header("location: " . "/password_reset.php" . "?error=sqlError"
            . "&code=105");
        exit();
    }

    $entryExists = $query->fetchColumn();
    return $entryExists ? true : false;
}

// delete password reset request from the database
function delete_pwd_request($mail)
{
    $sql = "DELETE FROM `resetPwd` WHERE `user_wwu_email`=?";

    try {
        get_login_db()->prepare($sql)->execute([$mail]);
    } catch (PDOException $e) {
        header("location: " . "/password_reset.php" . "?error=sqlError"
            . "&code=106");
        exit();
    }
}

// add password reset request to the database
function add_pwd_request($mail, $selector, $validator, $expires)
{
    // Hash the validator token
    try {
        $hashedToken = hash_user_pwd($validator);
    } catch (Exception $e) {
        display_exception_msg($e, "032");
        exit();
    }
    $insertRequest = "INSERT INTO `resetPwd` (`request_id`,"
        . "`user_wwu_email`, `request_selector`, `request_token`,"
        . "`request_expiration`) VALUE (NULL, :mail, :selector, :token, :date)";

    try {
        get_login_db()->prepare($insertRequest)->execute([
            'mail' => $mail,
            'selector' => $selector,
            'token' => $hashedToken,
            'date' => $expires
        ]);
    } catch (PDOException $e) {
        header("location: " . "/password_reset.php" . "?error=sqlError"
            . "&code=107");
        exit();
    }
}

// send a mail with password reset instructions
function send_pwd_reset_mail($mail, $resetUrl)
{
    $to = $mail;
    $subject = "Reset your Password | Web Security Challenges";
    $msg = "<p>You recently requested to reset your password. ";
    $msg .= "Use the link below to change it.</p>";
    $msg .= '<p><a href="' . $resetUrl . '">Reset my password!</p>';
    $msg .= "<p>This password reset request is only valid for the next ";
    $msg .= "<strong>15</strong> minutes.</p>";
    $msg .= "<p>If you didn't request this, please ignore this email. ";
    $msg .= "Your password won't change until you access the link above ";
    $msg .= "and create a new one.</p>";
    $msg .= "<p>If you cannot open the link above, try copying this link ";
    $msg .= "in your browser: ";
    $msg .= $resetUrl . "</p>";
    $header = "From: Websec Automailer <websec.automailer@gmail.com>\r\n";
    $header .= "Reply-To: websec.automailer@gmail.com\r\n";
    $header .= "Content-type: text/html\r\n";

    mail($to, $subject, $msg, $header);
}

// change user password in database
function change_password($username, $pwd, $newPwd, $confirmPwd)
{

    $redirectPath = "/user/change_password.php";

    // Check if new password is secure enough
    if (!validate_pwd($newPwd)) {
        header("location: " . $redirectPath . "?error=invalidPassword");
        exit();
    }
    // Check password confirmation
    else if ($newPwd !== $confirmPwd) {
        header("location: " . $redirectPath . "?error=passwordMismatch");
        exit();
    } else {

        // Get password from the DB
        $sql = "SELECT user_name,user_pwd_hash FROM users WHERE user_name=?";
        try {
            $stmt = get_login_db()->prepare($sql);
            $stmt->execute([$username]);
        } catch (PDOException $e) {
            header("location: " . $redirectPath . "?error=sqlError" . "&code=108");
            exit();
        }

        // Check if current password is correct
        $result = $stmt->fetch();
        if (verify_pwd($pwd, $result, $redirect = $redirectPath)) {

            try {
                $newPwdHash = hash_user_pwd($newPwd);
            } catch (Exception $e) {
                display_exception_msg($e, "033");
                exit();
            }

            $sql = "UPDATE `users` SET `user_pwd_hash`= :hash WHERE"
                . "`user_name` = :user";
            try {
                $stmt = get_login_db()->prepare($sql);
                $stmt->execute([
                    'hash' => $newPwdHash,
                    'user' => $username
                ]);
            } catch (PDOException $e) {
                header("location: " . $redirectPath . "?error=sqlError"
                    . "&code=109");
                exit();
            }

            // Success message
            header("location: " . $redirectPath . "?success=pwdChanged");
            exit();
        }
    }
}

// Set a new password after password reset request
function set_new_pwd($selector, $validator, $pwd, $confirmPwd, $requestURI)
{

    $completeURI = $requestURI . "?s=" . $selector . "&v=" . $validator;

    if (!validate_new_pwd($pwd, $confirmPwd)) {
        header("location: " . $completeURI . "&error=invalidPassword");
        exit();
    } else if ($pwd !== $confirmPwd) {
        header("location: " . $completeURI . "&error=passwordMismatch");
        exit();
    } else if (!verify_token($selector, $validator, $requestURI)) {
        header("location: " . LOGIN_PAGE . "?error=invalidToken");
        exit();
    } else {

        try {
            $pwdHash = hash_user_pwd($pwd);
        } catch (Exception $e) {
            display_exception_msg($e, "034");
            exit();
        }
        $mail = get_user_mail($selector);

        $sql = "UPDATE `users` SET `user_pwd_hash` = :pwd WHERE "
            . "`user_wwu_email`=:mail";

        try {
            $stmt = get_login_db()->prepare($sql);
            $stmt->execute([
                'pwd' => $pwdHash,
                'mail' => $mail
            ]);
        } catch (PDOException $e) {
            header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=110");
            exit();
        }

        header("location: " . LOGIN_PAGE . "?success=resetPwd");
        exit();
    }
}

// check if new password fulfills the requirements
function validate_new_pwd($pwd, $confirmPwd)
{
    if (empty($pwd) || empty($confirmPwd)) {

        return false;
    } else if (!validate_pwd($pwd)) {

        return false;
    } else {

        return true;
    }
}

// check if the token in the reset url are correct
function verify_token($selector, $validator, $requestURI)
{
    // Check if the tokens are both hexadecimal
    if (!ctype_xdigit($selector) || !ctype_xdigit($validator)) {
        return false;
    }

    $currentDate = date('U');

    $sql = "SELECT `request_token` FROM `resetPwd` WHERE "
        . "`request_selector`=? AND `request_expiration`>="
        . $currentDate;

    // get token from DB by selector
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$selector]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=111");
        exit();
    }

    // check if more than 1 valid request exists in the database
    $count = $stmt->rowCount();
    if ($count > 1) {
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=042");
        exit();
    } else if (!$result) {
        return false;
    } else {

        $tokenCheck = password_verify($validator, $result['request_token']);
        if ($tokenCheck) {
            return true;
        } else {
            return false;
        }
    }
}

// get the corresponding user e-mail to a given selector token
function get_user_mail($selector)
{
    $sql = "SELECT `user_wwu_email` FROM `resetPwd` WHERE `request_selector`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$selector]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=101");
        exit();
    }

    return $result['user_wwu_email'];
}

// update the field last login in the users table with current timestamp
function update_last_login($username)
{
    $sql = "UPDATE `users` SET `last_login`=:timestamp WHERE `user_name`=:user";
    $timestamp = date("Y-m-d H:i:s");

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([
            'timestamp' => $timestamp,
            'user' => $username
        ]);
    } catch (PDOException $e) {
        trigger_error("Code Error: last_login field could not be updated.");
        header("location: " . LOGIN_PAGE);
        exit();
    }
}

// check if registration is enabled
function is_registration_enabled()
{
    $file = CON . "registration_disabled";

    return file_exists($file) ? false : true;
}

// check if login is enabled
function is_login_enabled()
{
    $file = CON . "login_disabled";

    return file_exists($file) ? false : true;
}
