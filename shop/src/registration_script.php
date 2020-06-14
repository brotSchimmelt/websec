<?php
// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
// load DB connection
require(CON . "db_user_conn.php");
// load login functions
require(FUNC_LOGIN);


// check if username AND mail are okay
if (!validate_username($_POST['username']) && !validate_mail($_POST['mail'])) {
    header("Location: " . REGISTER_PAGE . "?error=invalidNameAndMail");
    exit();
}
// validate the username alone
else if (!validate_username($_POST['username'])) {
    $oldInput = "&mail=" . $_POST['mail'];
    header("Location: " . REGISTER_PAGE . "?error=invalidUsername" . $oldInput);
    exit();
}
// validate the mail adress alone
else if (!validate_mail($_POST['mail'])) {
    $oldInput = "&username=" . $_POST['username'];
    header("Location: " . REGISTER_PAGE . "?error=invalidMail" . $oldInput);
    exit();
}
// validate the password
else if (!validate_pwd($_POST['password'])) {
    $oldInput = "&username=" . $_POST['username'] . "&mail=" . $_POST['mail'];
    header("Location: " . REGISTER_PAGE . "?error=invalidPassword" . $oldInput);
    exit();
}
// check if the passwords match
else if ($_POST['password'] !== $_POST['confirmPassword']) {
    $oldInput = "&username=" . $_POST['username'] . "&mail=" . $_POST['mail'];
    header("Location: " . REGISTER_PAGE . "?error=passwordMismatch" . $oldInput);
    exit();
} else {
    // check if username already exits in the db
    try {
        $duplicateQuery = $pdoLogin->prepare("SELECT 1 FROM users WHERE user_name = ?");
        $duplicateQuery->execute([$_POST['username']]);
    } catch (Exception $e) {
        header("Location: " . REGISTER_PAGE . "?error=sqlError");
        exit();
    }
    $nameExists = $duplicateQuery->fetchColumn();
    if ($nameExists) {
        $oldInput = "&mail=" . $_POST['mail'];
        header("Location: " . REGISTER_PAGE . "?error=nameTaken" . $oldInput);
        exit();
    }
    // add user to the db
    else {
        $pwdHash = hash_user_pwd($_POST['password']);

        try {
            $insertUser = "INSERT INTO users (user_name, user_mail, user_pwd_hash) VALUE (?, ?, ?)";
            $pdoLogin->prepare($insertUser)->execute([$_POST['username'], $_POST['mail'], $pwdHash]);
        } catch (Exception $e) {
            header("Location: " . REGISTER_PAGE . "?error=sqlError");
            exit();
        }
        // redirect back to login page
        header("location: " . LOGIN_PAGE . "?signup=success");
        exit();
    }
}
