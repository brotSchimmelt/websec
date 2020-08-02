<?php

function get_login_db()
{
    static $dbLogin;

    if ($dbLogin instanceof PDO) {
        return $dbLogin;
    }

    try {
        $dbLogin = new PDO(DSN_LOGIN, DB_USER_LOGIN, DB_PWD_LOGIN, OPTIONS_LOGIN);
    } catch (PDOException $e) {
        exit("Unable to connect to the database :(");
    }
    return $dbLogin;
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

function validate_mail($mailAddress)
{
    if ((mb_strstr($mailAddress, "@") == "@uni-muenster.de") || ((mb_strstr($mailAddress, "@") == "@wi.uni-muenster.de"))) {
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

function do_login($username, $mail, $adminFlag)
{
    if (session_status() == PHP_SESSION_NONE) {
        // Session has not yet started
        session_start();
    }

    $token = bin2hex(openssl_random_pseudo_bytes(32));

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
    // check if username already exits in the db
    try {
        $duplicateQuery = get_login_db()->prepare("SELECT 1 FROM users WHERE user_name = ?");
        $duplicateQuery->execute([$username]);
    } catch (Exception $e) {
        header("Location: " . REGISTER_PAGE . "?error=sqlError");
        exit();
    }
    $nameExists = $duplicateQuery->fetchColumn();
    if ($nameExists) {
        $oldInput = "&mail=" . $mail;
        header("Location: " . REGISTER_PAGE . "?error=nameTaken" . $oldInput);
        exit();
    }
    // add user to the db
    else {
        $pwdHash = hash_user_pwd($password);
        $fakeXSSCookieID = bin2hex(openssl_random_pseudo_bytes(16));

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

        create_sqli_db($username, $mail);

        // redirect back to login page
        header("location: " . LOGIN_PAGE . "?signup=success");
        exit();
    }
}
