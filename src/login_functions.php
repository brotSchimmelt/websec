<?php

/**
 * This file contains all functions that are relevant for user login /
 * registration.
 */

/**
 * Get the PDO connection for the login DB.
 *
 * Uses the credentials defined in the config.php file.
 *
 * @return \PDO the login database connection.
 */
function get_login_db()
{
    // ensure only one connection at a time is alive
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

/**
 * Check if a POST variable is set and not empty.
 *
 * Check if a POST variable with a given name was set and is not empty.
 *
 * @param string $varName POST variable name.
 * @return bool POST variable status.
 */
function post_var_set($varName)
{
    if (isset($_POST[$varName]) && !empty($_POST[$varName])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if a GET variable is set and not empty.
 *
 * Check if a GET variable with a given name was set and is not empty.
 *
 * @param string $varName GET variable name.
 * @return bool GET variable status.
 */
function get_var_set($varName)
{
    if (isset($_GET[$varName]) && !empty($_GET[$varName])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if a already user exits.
 *
 * Check if the number of users with the same name is greater than 1 and show
 * appropriate user messages.
 *
 * @param int $numUser Number of times a user name exits in the database.
 * @return bool User status.
 */
function check_user_exists($numUsers)
{
    if ($numUsers > 1) {

        // check if there is more than 1 entry for that name
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=041");
        return false;
    } else if ($numUsers < 1) {

        // user not found
        header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
        return false;
    } else {
        return true;
    }
}

/**
 * Check if password is correct.
 *
 * Check if the given password matches the password hash in the login database
 * and catch login errors.
 *
 * @param string $pwd Given password.
 * @param array $resultArray User data from the login database.
 * @param string $redirect Path for error redirect.
 * @return bool Password status.
 */
function verify_pwd($pwd, $resultArray, $redirect = LOGIN_PAGE)
{
    $pwdTest = password_verify($pwd, $resultArray['user_pwd_hash']);
    if ($pwdTest) {
        return true;
    } else if (!$pwdTest) {

        // sleep(3); // 3 seconds
        // send user back if password does not match
        header("location: " . $redirect . "?error=wrongCredentials");
        return false;
    } else {

        // sleep(3); // 3 second
        // just to catch any errors in the 'password_verify' function
        header("location: " . $redirect . "?error=internalError");
        return false;
    }
}

/**
 * Check if the user name fulfills the requirements.
 *
 * Check if a given user name is between 2 and 24 characters long and contains
 * only letters and numbers.
 *
 * @param string $username User name to verify.
 * @return bool User name status.
 */
function validate_username($username)
{
    if (!preg_match("/^[A-Za-z0-9]*$/", $username)) {
        return false;
    } else if ((mb_strlen($username) > 24) || (mb_strlen($username) < 2)) {
        return false;
    }
    return true;
}

/**
 * Check if a given mail address is valid.
 *
 * Check if the address has a valid format and uses a allowed domain.
 *
 * @param string $mail User mail to check.
 * @return bool Mail Status.
 */
function validate_mail($mail)
{
    // check if user input is a valid mail format
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
        header("location: " . REGISTER_PAGE . "?error=invalidMailFormat");
        return false;
    }

    // Note: Add other valid domains in settings.json or in the admin panel
    $validDomains = get_allowed_domains();

    // check if a valid domain us used
    $needle = mb_strstr($mail, "@");
    if (in_array($needle, $validDomains)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if the password has a valid format.
 *
 * Check if the given password is not empty and at least 8 characters long.
 *
 * @param string $pwd Given password.
 * @return bool Password status.
 */
function validate_pwd($pwd)
{
    if (!$pwd || mb_strlen($pwd) < 8) {
        return false;
    }
    return true;
}

/**
 * Hash the user password.
 *
 * Hash the user password with the php default method.
 *
 * @param string $pwd Password to hash.
 * @return string Password hash.
 * @throws Exception If the hash creation fails.
 */
function hash_user_pwd($pwd)
{
    $hash = password_hash($pwd, PASSWORD_DEFAULT, ['cost' => 13]);
    // check if the hash was successfully created
    if (!$hash) {
        throw new Exception("Hash creation failed.");
    }
    return $hash;
}

/**
 * Check if the given entry already exists.
 *
 * Check with a given SQL query if the entry is already present in the login
 * database.
 *
 * @param string $entry Entry to check.
 * @param string $sql SQL query to check entry.
 * @return bool Result flag.
 */
function check_entry_exists($entry, $sql)
{
    // Fake user array
    $fakeUsers = get_blocked_usernames();

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
        foreach ($fakeUsers as $fakeName) {
            if ($fakeName == $entry) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Check if user input is valid.
 *
 * Check user name, mail and password for validity with separat functions.
 *
 * @param string $username User name.
 * @param string $mail User mail address.
 * @param string $password User password.
 * @param string $confirmPWD Repeated password.
 * @return bool Result of the check.
 */
function validate_registration_input($username, $mail, $password, $confirmPWD)
{
    // check if username AND mail are okay
    if (!validate_username($username) && !validate_mail($mail)) {
        header("location: " . REGISTER_PAGE . "?error=invalidNameAndMail");
        return false;
    }
    // validate the username alone
    else if (!validate_username($username)) {
        $input = "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=invalidUsername" . $input);
        return false;
    }
    // validate the mail adress alone
    else if (!validate_mail($mail)) {
        $input = "&username=" . $username;
        header("location: " . REGISTER_PAGE . "?error=invalidMail" . $input);
        return false;
    }
    // validate the password
    else if (!validate_pwd($password)) {
        $input = "&username=" . $username . "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=invalidPassword" . $input);
        return false;
    }
    // check if the passwords match
    else if ($password !== $confirmPWD) {
        $input = "&username=" . $username . "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=passwordMismatch" . $input);
        return false;
    } else {
        // all checks passed!
        return true;
    }
}

/**
 * Log the user in.
 *
 * Create a PHP session and set the user name, the mail address, the admin flag
 * and the unlocked flag.
 *
 * @param string $username The users name.
 * @param string $mail The mail address.
 * @param int $adminFlag Flag if the user is admin or not.
 * @param int $unlockedFlag Flag if the user is unlocked or not.
 */
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

    // set user to exploit for CSRF challenge
    $_SESSION['userCSRF'] = "elliot";
    // set fake CSRF token for 'hard' difficulty
    $_SESSION['fakeCSRFToken'] = str_shuffle("fakeUserToken13579");

    if ($adminFlag == 1) {
        $_SESSION['userIsAdmin'] = $adminFlag;
    }

    if ($unlockedFlag == 1) {
        $_SESSION['userIsUnlocked'] = $unlockedFlag;
    }

    update_last_login($username);

    set_user_cookies($username);

    check_sqli_db($username, $mail);
}

/**
 * Ensure the SQLite database exits.
 *
 * Check for a given user if the SQLite database for the SQLi challenge already
 * exists and if not, create it.
 *
 * @param string $username Name of the user.
 * @param string $mail Mail address.
 */
function check_sqli_db($username, $mail)
{

    $dbName = DAT . slug($username) . ".sqlite";

    if (!file_exists($dbName)) {
        create_sqli_db($username, $mail);
    }
}

/**
 * Validate the user credentials for the login.
 *
 * Compare the user name (or mail) and the password with the login database.
 *
 * @param string $userInput Either the user name or the mail address.
 * @param string $pwd Password.
 */
function try_login($userInput, $pwd)
{
    // check if user used email or username for login
    if (filter_var($userInput, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT user_name,user_pwd_hash,user_wwu_email,is_admin,is_unlocked ";
        $sql .= "FROM users WHERE user_wwu_email=?";
    } else {
        $sql = "SELECT user_name,user_pwd_hash,user_wwu_email,is_admin,is_unlocked ";
        $sql .= "FROM users WHERE user_name=?";
    }

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$userInput]);
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
            header("location: " . MAIN_PAGE);
            exit();
        }
    }
}

/**
 * Check if user data already exits in the database.
 *
 * Check if the user name or the mail is already in the database. If not, start
 * the registration process.
 *
 * @param string $username User name.
 * @param string $mail Mail address.
 * @param string $password User password.
 * @return bool Registration status.
 */
function try_registration($username, $mail, $password)
{
    $usernameSQL = "SELECT 1 FROM `users` WHERE `user_name` = ?";
    $mailSQL = "SELECT 1 FROM `users` WHERE `user_wwu_email` = ?";
    $oldChallengeSQL = "SELECT 1 FROM `challengeStatus` WHERE `user_name` = ?";
    $oldCookieSQL = "SELECT 1 FROM `fakeCookie` WHERE `user_name` = ?";

    // check if username or mail already exits in the db
    if (check_entry_exists($username, $usernameSQL)) {

        $oldInput = "&mail=" . $mail;
        header("location: " . REGISTER_PAGE . "?error=nameError" . $oldInput);
        return false;
    } else if (check_entry_exists($mail, $mailSQL)) {

        $oldInput = "&username=" . $username;
        header("location: " . REGISTER_PAGE . "?error=mailTaken" . $oldInput);
        return false;
    } else  if (
        check_entry_exists($username, $oldChallengeSQL) ||
        check_entry_exists($username, $oldCookieSQL)
    ) {

        header("location: " . REGISTER_PAGE . "?error=doubleEntry");
        return false;
    } else {
        $registration = do_registration($username, $mail, $password);
        return $registration;
    }
}

/**
 * Register the user.
 *
 * Write the user data to the login database.
 *
 * @param string $username User name.
 * @param string $mail Mail address.
 * @param string $password User password.
 * @return bool Registration status.
 */
function do_registration($username, $mail, $password)
{
    try {
        $pwdHash = hash_user_pwd($password);
    } catch (Exception $e) {
        display_exception_msg($e, "031");
        exit();
    }

    try {
        $reflectiveXSSCookie = get_random_token(16);
        $storedXSSCookie = get_random_token(16);
        $fakeTokenCSRF = get_random_token(16);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    // insert user into the database
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

    // insert cookies into the database
    $insertCookie = "INSERT INTO `fakeCookie` (`id`, `user_name`, "
        . "`reflective_xss`, `stored_xss`, `fake_token`) VALUE (NULL, :user, "
        . ":reflective, :stored, :token)";

    try {
        get_login_db()->prepare($insertCookie)->execute([
            'user' => $username,
            'reflective' => $reflectiveXSSCookie,
            'stored' => $storedXSSCookie,
            'token' => $fakeTokenCSRF
        ]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=119");
        exit();
    }

    // Create personal DB for SQLi challenge
    try {
        create_sqli_db($username, $mail);
    } catch (Exception $e) {
        display_exception_msg($e, "051");
        exit();
    }

    // set initial challenge status
    $insertChallenge = "INSERT INTO `challengeStatus` (`id`, `user_name`, "
        . "`reflective_xss`, `stored_xss`, `sqli`, `csrf`, `csrf_referrer`, "
        . "`reflective_xss_hard`, `stored_xss_hard`, `sqli_hard`, `csrf_hard`, "
        . "`csrf_referrer_hard`) VALUE (NULL, :user, 0, 0, 0, 0, 0, 0, 0, 0, "
        . "0, 0)";

    try {
        get_login_db()->prepare($insertChallenge)->execute([
            'user' => $username
        ]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=119");
        exit();
    }

    // set initial challenge solution database
    $insertSolutions = "INSERT INTO `challenge_solutions` (`id`, `user_name`) "
        . "VALUE (NULL, :user)";

    try {
        get_shop_db()->prepare($insertSolutions)->execute([
            'user' => $username
        ]);
    } catch (PDOException $e) {
        header("location: " . REGISTER_PAGE . "?error=sqlError" . "&code=119");
        exit();
    }

    // redirect back to login page
    header("location: " . LOGIN_PAGE . "?success=signup");
    return true;
}

/**
 * Generate a custom random token.
 *
 * Generate a random token with a given length using the open ssl random pseudo
 * bytes function.
 *
 * @param int $length Length of the token.
 * @return string|bool Random token.
 * @throws Exception If the token creation fails.
 */
function get_random_token($length)
{
    if ($length <= 0) {
        trigger_error("Code Error: Token length cannot be 0 or negative!");
        return false;
    }

    $token = bin2hex(openssl_random_pseudo_bytes($length));
    if ($token === false) {
        throw new Exception("Token creation failed.");
    }

    // trim the new token to the predefined length
    return substr($token, 0, $length);
}

/**
 * Create a reset request for the user password.
 *
 * Create a password reset request for a user account identified by its mail
 * address in the login database.
 *
 * @param string $mail Mail address of the user account.
 * @return bool Password reset status.
 */
function do_pwd_reset($mail)
{

    $mailCheckSQL = "SELECT 1 FROM `users` WHERE `user_wwu_email` = ?";
    $mailExists = check_entry_exists($mail, $mailCheckSQL);
    $resetStatus = false;

    if ($mailExists) {
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
        $resetStatus = send_pwd_reset_mail($mail, $url);
    }

    // Show success message either way
    // sleep(3); // to avoid spam
    header("location: " . LOGIN_PAGE .  "?success=requestProcessed");
    return $resetStatus;
}

/**
 * Check if a password reset request exists.
 *
 * Check if a password reset request exists in the database for a user account.
 *
 * @param string $mail Mail address of a user account.
 * @return bool Request status.
 */
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

/**
 * Remove a password reset request.
 *
 * Delete a password reset request from the login database.
 *
 * @param string $mail Mail address of a user account.
 */
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

/**
 * Add a password reset request.
 *
 * Add a password reset request for a given mail address to the login database.
 *
 * @param string $mail User account mail address.
 * @param string $selector Identifier for the request in the database.
 * @param string $validator Token to validate the request.
 * @param string $expires Time to live for the request.
 */
function add_pwd_request($mail, $selector, $validator, $expires)
{
    // hash the validator token
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

/**
 * Send password reset mail to user.
 *
 * Send a mail with instructions to reset the password to the given mail address.
 *
 * @param string $mail User mail address.
 * @param string $resetUrl URL with token to the password reset form.
 * @return bool True if the mail was successfully accepted for delivery, false
 * otherwise.
 */
function send_pwd_reset_mail($mail, $resetUrl)
{
    $to = $mail;
    $subject = "Reset your Password | Web Security Challenges";
    $name = get_user_name($mail);
    $msg = "Hi, " . $name . "!<br><br>"
        . "<p>You recently requested to reset your password for the WebSec "
        . "hacking platform. Use the link below to change it.</p>"
        . '<p><a href="' . $resetUrl . '">Reset my password!</a></p>'
        . "<p>This password reset request is only valid for the next "
        . "<strong>15</strong> minutes.</p>"
        . "<p>If you didn't request this, please ignore this email. "
        . "Your password won't change until you access the link above "
        . "and create a new one.</p>"
        . "<p><small>If you cannot open the link above, try copying this link "
        . "to your browser: "
        . $resetUrl . "</small></p>";
    $header = "From: Websec Automailer <websec@wi.uni-muenster.de>\r\n"
        . "Reply-To: websec@wi.uni-muenster.de\r\n"
        . "Content-type: text/html\r\n";

    $mailStatus = send_mail($to, $subject, $msg, $header);
    return $mailStatus;
}

/**
 * Update user password.
 *
 * Update the user password in the login database.
 *
 * @param string $username User name.
 * @param string $pwd User password.
 * @param string $newPwd New user password.
 * @param string $confirmPWD Repeated new user password.
 * @return bool Password status.
 */
function change_password($username, $pwd, $newPwd, $confirmPwd)
{

    $redirectPath = "/user/change_password.php";

    // Check if new password is secure enough
    if (!validate_pwd($newPwd)) {
        header("location: " . $redirectPath . "?error=invalidPassword");
        return false;
    }
    // Check password confirmation
    else if ($newPwd !== $confirmPwd) {
        header("location: " . $redirectPath . "?error=passwordMismatch");
        return false;
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

        // Check if user was found
        $result = $stmt->fetch();
        if(!$result) {
          header("location: " . $redirectPath . "?error=wrongCredentials");
          return false;
        }

        // check if current password is correct
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
            return true;
        }
    }
}

/**
 * Update password after password reset.
 *
 * Set new password after password reset request was fulfilled.
 *
 * @param string $selector Identifier for the request in the database.
 * @param string $validator Validation token for the request.
 * @param string $pwd New user password.
 * @param string $confirmPWD Repeated new user password.
 * @param string $requestURI URI to the request form.
 * @return bool Password status.
 */
function set_new_pwd($selector, $validator, $pwd, $confirmPwd, $requestURI)
{

    $completeURI = $requestURI . "?s=" . $selector . "&v=" . $validator;

    if (!validate_new_pwd($pwd, $confirmPwd)) {
        header("location: " . $completeURI . "&error=invalidPassword");
        return false;
    } else if ($pwd !== $confirmPwd) {
        header("location: " . $completeURI . "&error=passwordMismatch");
        return false;
    } else if (!verify_token($selector, $validator, $requestURI)) {
        header("location: " . LOGIN_PAGE . "?error=invalidToken");
        return false;
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
        return true;
    }
}

/**
 * Check if new password fulfills the requirements.
 *
 * Check if the new password is not empty and fulfills the default password
 * requirements from validate_pwd().
 *
 * @param string $pwd Password.
 * @param string $confirmPwd Repeated password.
 * @return bool Password status.
 */
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

/**
 * Verify the request token.
 *
 * Check if the selector and validator match the database entries.
 *
 * @param string $selector Request identifier.
 * @param string $validator Request token.
 * @param string $requestURI URI to the reset form.
 * @return bool Request status.
 */
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
        return false;
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

/**
 * Get the user mail address.
 *
 * Get the corresponding user e-mail to a given selector token.
 *
 * @param string $selector Request identifier.
 * @return string User account mail.
 */
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

    return ($result) ? $result['user_wwu_email'] : $result;
}

/**
 * Update last login.
 *
 * Set a new date for the last login field in the login database for a give
 * user.
 *
 * @param string $username User name.
 */
function update_last_login($username)
{

    $_SESSION['pwdChangeReminder'] = set_change_pwd_reminder($username);

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

/**
 * Set change password reminder.
 *
 * Check if a user has to change password.
 *
 * @param string $username User name.
 * @return bool Change status.
 */
function set_change_pwd_reminder($username)
{
    // set flag for default password reminder
    if (("administrator" == $username) && is_null(get_last_login($username))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Set reflective XSS challenge cookie.
 *
 * Get challenge cookies from the database and store them in the user session.
 * Set the reflective XSS challenge cookie.
 *
 * @param string $username User name.
 */
function set_user_cookies($username)
{
    // get cookies from database
    $sql = "SELECT `reflective_xss`,`stored_xss` FROM `fakeCookie` WHERE "
        . "`user_name`=?";
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError" . "&code=121");
        exit();
    }

    // set cookies
    setcookie("XSS_YOUR_SESSION", $result['reflective_xss'], 0, "/");
    $_SESSION['reflectiveXSS'] = $result['reflective_xss'];
    $_SESSION['storedXSS'] = $result['stored_xss'];
}

/**
 * Get last login.
 *
 * Get the last login date from the login database for a given user.
 *
 * @param string $username User name.
 * @return string Last login.
 */
function get_last_login($username)
{

    $sql = "SELECT `last_login` FROM `users` WHERE `user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (Exception $e) {
        display_exception_msg($e, "127");
        exit();
    }

    return $result['last_login'];
}
