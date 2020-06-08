<?php
// load DB connection
require("$_SERVER[DOCUMENT_ROOT]/../config/db_user_conn.php");
// load extra functions
require("$_SERVER[DOCUMENT_ROOT]/../src/functions.php");

// path to login page
$loginPage = "index.php";
$mainPage = "main.php";

// get password and user name from POST
$username = filter_input(INPUT_POST, 'loginUsername');
$pwd = filter_input(INPUT_POST, 'loginPwd');

if (empty($username) || empty($pwd)) {
    header("location: " . $loginPage . "?error=emptyFields");
    exit();
} else {

    // get pwd and username from db
    try {
        $sql = $pdoLogin->prepare("SELECT * FROM users WHERE user_name=?");
        $sql->execute([$username]);
    } catch (Exception $e) {
        header("location: " . $loginPage . "?error=sqlError");
        exit();
    }

    // check if user exists
    $numUsers = $sql->rowCount();
    if ($numUsers > 1) {
        // check if there is more than 1 entry for that name
        header("location: " . $loginPage . "?error=sqlError");
        exit();
    } else if ($numUsers < 1) {
        // user not found
        header("location: " . $loginPage . "?error=wrongCredentials");
        exit();
    } else {
        // validate the entered password
        $result = $sql->fetch();
        $pwdTest = password_verify($pwd, $result['user_pwd_hash']);
        if ($pwdTest) {
            // log user in
            session_start();
            $_SESSION['uName'] = $result['user_name'];

            header("location: " . $mainPage . "?login=success");
        } else if (!$pwdTest) {
            // send user back if password does not match
            header("location: " . $loginPage . "?error=wrongCredentials");
            exit();
        } else {
            // just to catch any errors in the 'password_verify' function
            header("location: " . $loginPage . "?error=internalError");
            exit();
        }
    }
}
