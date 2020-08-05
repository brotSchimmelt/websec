<?php

function get_login_db()
{
    static $dbLogin; // to ensure only one connection

    if ($dbLogin instanceof PDO) {
        return $dbLogin;
    }

    try {
        $dbLogin = new PDO(DSN_LOGIN, DB_USER_LOGIN, DB_PWD_LOGIN, OPTIONS_LOGIN);
    } catch (PDOException $e) {
        exit("Unable to connect to the database :("); // TODO: Add helpful error message
    }
    return $dbLogin;
}

function post_var_set($varName)
{
    if (isset($_POST[$varName]) && !empty($_POST[$varName])) {
        return true;
    } else {
        return false;
    }
}

function get_var_set($varName)
{
    if (isset($_GET[$varName]) && !empty($_GET[$varName])) {
        return true;
    } else {
        return false;
    }
}

function check_user_exists($numUsers)
{
    if ($numUsers > 1) {
        // wait for 3 seconds
        sleep(3);
        // check if there is more than 1 entry for that name
        header("location: " . LOGIN_PAGE . "?error=sqlError");
        exit();
    } else if ($numUsers < 1) {
        // wait for 3 seconds
        sleep(3);
        // user not found
        header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
        exit();
    } else {
        return true;
    }
}

function verify_pwd($pwd, $resultArray)
{
    $pwdTest = password_verify($pwd, $resultArray['user_pwd_hash']);
    if ($pwdTest) {
        return true;
    } else if (!$pwdTest) {
        // wait for 3 seconds
        sleep(3);
        // send user back if password does not match
        header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
        exit();
    } else {
        // wait for 3 seconds
        sleep(3);
        // just to catch any errors in the 'password_verify' function
        header("location: " . LOGIN_PAGE . "?error=internalError");
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

function validate_mail($mail)
{
    // Add other valid domains here:
    $validDomains = array("@uni-muenster.de", "@wi.uni-muenster.de", "@gmail.com");

    $needle = mb_strstr($mail, "@");
    if (in_array($needle, $validDomains)) {
        return true;
    }
    return false;
}

function validate_pwd($pwd)
{
    if (!$pwd || mb_strlen($pwd) < 8) {
        return false;
    }
    return true;
}

function hash_user_pwd($pwd)
{
    $hash = password_hash($pwd, PASSWORD_DEFAULT, ['cost' => 13]);
    // check if the hash was successfully created
    if (!$hash) {
        throw new Exception("Hash creation failed: Error Code 42. Please post this error in Learnweb.");
    }
    return $hash;
}

function check_entry_exists($entry, $sql)
{
    // Fake user array
    $fakeUser = array("admin", "elliot", "l337_h4ck3r");

    // Get entries from DB
    try {
        $duplicateQuery = get_login_db()->prepare($sql);
        $duplicateQuery->execute([$entry]);
    } catch (Exception $e) {
        header("Location: " . REGISTER_PAGE . "?error=sqlError");
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

function validate_registration_input($username, $mail, $password, $confirmPassword)
{
    // check if username AND mail are okay
    if (!validate_username($username) && !validate_mail($mail)) {
        header("Location: " . REGISTER_PAGE . "?error=invalidNameAndMail");
        exit();
    }
    // validate the username alone
    else if (!validate_username($username)) {
        $oldInput = "&mail=" . $mail;
        header("Location: " . REGISTER_PAGE . "?error=invalidUsername" . $oldInput);
        exit();
    }
    // validate the mail adress alone
    else if (!validate_mail($mail)) {
        $oldInput = "&username=" . $username;
        header("Location: " . REGISTER_PAGE . "?error=invalidMail" . $oldInput);
        exit();
    }
    // validate the password
    else if (!validate_pwd($password)) {
        $oldInput = "&username=" . $username . "&mail=" . $mail;
        header("Location: " . REGISTER_PAGE . "?error=invalidPassword" . $oldInput);
        exit();
    }
    // check if the passwords match
    else if ($password !== $confirmPassword) {
        $oldInput = "&username=" . $username . "&mail=" . $mail;
        header("Location: " . REGISTER_PAGE . "?error=passwordMismatch" . $oldInput);
        exit();
    } else {
        // all checks passed!
        return true;
    }
}

function do_login($username, $mail, $adminFlag)
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session has not yet started
        session_start();
    }

    $token = get_random_token(32);

    $_SESSION['userToken'] = $token;
    $_SESSION['userName'] = $username;
    $_SESSION['userMail'] = $mail;
    $_SESSION['userLoginStatus'] = 1;

    if ($adminFlag == 1) {
        $_SESSION['userIsAdmin'] = $adminFlag;
    }
}

function try_login($username, $pwd)
{
    // Get pwd and username from DB
    try {
        $sql = get_login_db()->prepare("SELECT user_name,user_pwd_hash,user_wwu_email,is_admin FROM users WHERE user_name=?");
        $sql->execute([$username]);
    } catch (Exception $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError");
        exit();
    }

    // check if user exists
    $numUsers = $sql->rowCount();
    if (check_user_exists($numUsers)) {

        // validate the entered password
        $result = $sql->fetch();
        if (verify_pwd($pwd, $result)) {

            do_login($result['user_name'], $result['user_wwu_email'], $result['is_admin']);
            header("location: " . MAIN_PAGE . "?login=success");
            exit();
        }
    }
}

function try_registration($username, $mail, $password)
{
    // check if username or mail already exits in the db
    if (check_entry_exists($username, "SELECT 1 FROM users WHERE user_name = ?")) {

        $oldInput = "&mail=" . $mail;
        header("Location: " . REGISTER_PAGE . "?error=nameTaken" . $oldInput);
        exit();
    } else if (check_entry_exists($mail, "SELECT 1 FROM users WHERE user_wwu_email = ?")) {

        $oldInput = "&username=" . $username;
        header("Location: " . REGISTER_PAGE . "?error=mailTaken" . $oldInput);
        exit();
    } else {
        do_registration($username, $mail, $password);
    }
}

function do_registration($username, $mail, $password)
{
    $pwdHash = hash_user_pwd($password);
    $fakeXSSCookieID = get_random_token(16);

    try {
        $insertUser = "INSERT INTO users (user_id, user_name, user_wwu_email, user_pwd_hash, is_unlocked, is_admin, timestamp, xss_fake_cookie_id) VALUE (NULL, :user, :mail, :pwd_hash, '0', '0', :timestamp, :cookie_id)";
        get_login_db()->prepare($insertUser)->execute([
            'user' => $username,
            'mail' => $mail,
            'pwd_hash' => $pwdHash,
            'timestamp' => date("Y-m-d H:i:s"),
            'cookie_id' => $fakeXSSCookieID
        ]);
    } catch (Exception $e) {
        header("Location: " . REGISTER_PAGE . "?error=sqlError");
        exit();
    }

    // Create personal DB for SQLi challenge
    create_sqli_db($username, $mail);

    // redirect back to login page
    header("location: " . LOGIN_PAGE . "?signup=success");
    exit();
}

function get_random_token($length)
{
    if ($length <= 0) {
        trigger_error("Error: Token length cannot be 0 or negative!");
    }

    $token = bin2hex(openssl_random_pseudo_bytes($length));
    if ($token === false) {
        trigger_error("Error: Random token could not be generated!");
    }
    return substr($token, 0, $length);
}

function do_pwd_reset($mail)
{

    $selector = get_random_token(16); // TODO: add explanation
    $validator = get_random_token(32); // TODO: add explanation
    $expires = date('U') + 1200; // Token expires after 20 min (1200 s)

    // URL for redirect to password reset page
    $url = SITE_URL . "/test_pwd_reset.php?selector=" . $selector . "&validator=" . $validator;

    // Check if an old entry already exists for this mail and delete it
    if (check_pwd_request_status($mail)) {
        delete_pwd_request($mail);
    }

    // Add request to DB
    add_pwd_request($mail, $selector, $validator, $expires);

    // Send mail with reset instructions to user
    send_pwd_reset_mail($mail, $url);

    // Show success message
    header("location: " . "/password_reset.php?success=resetPwd");
    exit();
}

function check_pwd_request_status($mail)
{
    $sql = "SELECT * FROM `resetPwd` WHERE `user_wwu_email`=?";

    try {
        $query = get_login_db()->prepare($sql);
        $query->execute([$mail]);
    } catch (Exception $e) {
        header("Location: " . "/password_reset.php" . "?error=sqlError");
        exit();
    }

    $entryExists = $query->fetchColumn();
    return $entryExists ? true : false;
}

function delete_pwd_request($mail)
{
    $sql = "DELETE FROM `resetPwd` WHERE `user_wwu_email`=?";
    get_login_db()->prepare($sql)->execute([$mail]);
}

function add_pwd_request($mail, $selector, $validator, $expires)
{
    // Hash the validator
    $hashedToken = hash_user_pwd($validator);

    try {
        $insertRequest = "INSERT INTO `resetPwd` (`request_id`, `user_wwu_email`, `request_selector`, `request_token`, `request_expiration`) VALUE (NULL, :mail, :selector, :token, :date)";
        get_login_db()->prepare($insertRequest)->execute([
            'mail' => $mail,
            'selector' => $selector,
            'token' => $hashedToken,
            'date' => $expires
        ]);
    } catch (Exception $e) {
        header("Location: " . "/password_reset.php" . "?error=sqlError");
        exit();
    }
}

function send_pwd_reset_mail($mail, $resetUrl)
{
    $to = $mail;
    $subject = "Reset your Password | Web Security Challenges";
    $msg = "<p>You recently requested to reset your password. Use the link below to change it.</p>";
    $msg .= '<p><a href="' . $resetUrl . '">Reset my password!</p>';
    $msg .= "<p>This password reset request is only valid for the next <strong>15</strong> minutes.</p>";
    $msg .= "<p>If you didn't request this, please ignore this email. Your password won't change until you access the link above and create a new one.</p>";
    $header = "From: Websec Automailer <websec.automailer@gmail.com>\r\n";
    $header .= "Reply-To: websec.automailer@gmail.com\r\n";
    $header .= "Content-type: text/html\r\n";

    mail($to, $subject, $msg, $header);
}
