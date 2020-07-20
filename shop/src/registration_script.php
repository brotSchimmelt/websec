<?php
// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN); // Login db credentials

// Load custom libraries
// require(FUNC_BASE);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);


// Load POST or GET variables and sanitize input BELOW this comment
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
$mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, "password");
$confirmPassword = filter_input(INPUT_POST, "confirmPassword");


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
