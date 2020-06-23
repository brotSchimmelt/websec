<?php
// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN); // Login db credentials

// Load custom libraries
// require(FUNC_BASE);
require(FUNC_LOGIN);


// Load POST or GET variables and sanitize input BELOW this comment
$username = filter_input(INPUT_POST, 'loginUsername');
$pwd = filter_input(INPUT_POST, 'loginPwd');

if (empty($username) || empty($pwd)) {
    header("location: " . LOGIN_PAGE . "?error=emptyFields");
    exit();
} else {

    // get pwd and username from db
    try {
        $sql = $pdoLogin->prepare("SELECT user_name,user_pwd_hash,user_wwu_email,is_admin FROM users WHERE user_name=?");
        $sql->execute([$username]);
    } catch (Exception $e) {
        header("location: " . LOGIN_PAGE . "?error=sqlError");
        exit();
    }

    // check if user exists
    $numUsers = $sql->rowCount();
    if ($numUsers > 1) {
        // check if there is more than 1 entry for that name
        header("location: " . LOGIN_PAGE . "?error=sqlError");
        exit();
    } else if ($numUsers < 1) {
        // user not found
        header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
        exit();
    } else {
        // validate the entered password
        $result = $sql->fetch();
        $pwdTest = password_verify($pwd, $result['user_pwd_hash']);
        if ($pwdTest) {

            do_login($result['user_name'], $result['user_wwu_email'], $result['is_admin']);

            header("location: " . MAIN_PAGE . "?login=success");
            exit();
        } else if (!$pwdTest) {
            // send user back if password does not match
            header("location: " . LOGIN_PAGE . "?error=wrongCredentials");
            exit();
        } else {
            // just to catch any errors in the 'password_verify' function
            header("location: " . LOGIN_PAGE . "?error=internalError");
            exit();
        }
    }
}
