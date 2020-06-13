<?php
// load config files
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
// load DB connection
require(CON . "db_user_conn.php");
// load extra functions
require(FUNC_LOGIN);


// get password and user name from POST
$username = filter_input(INPUT_POST, 'loginUsername');
$pwd = filter_input(INPUT_POST, 'loginPwd');

if (empty($username) || empty($pwd)) {
    header("location: " . LOGIN_PAGE . "?error=emptyFields");
    exit();
} else {

    // get pwd and username from db
    try {
        $sql = $pdoLogin->prepare("SELECT * FROM users WHERE user_name=?");
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
            // log user in
            session_start();
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['user_mail'] = $result['user_mail'];
            $_SESSION['user_login_status'] = 1;

            header("location: " . MAIN_PAGE . "?login=success");
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
